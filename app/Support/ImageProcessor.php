<?php

declare(strict_types=1);

namespace App\Support;

use GdImage;
use RuntimeException;

/**
 * Tratamento de imagens enviadas pelo painel: valida, corrige a orientação,
 * redimensiona e comprime até caber no peso máximo configurado.
 *
 * Usa apenas a extensão GD, presente por padrão no PHP 8.3.
 */
final class ImageProcessor
{
    /** Formatos que o usuário pode liberar nas configurações. */
    public const FORMATS = ['jpg' => 'JPEG (.jpg)', 'webp' => 'WebP (.webp)', 'png' => 'PNG (.png)'];

    /** Qualidade mínima aceitável ao comprimir em busca do peso alvo. */
    private const MIN_QUALITY = 40;

    /** @var array<string, mixed> */
    private array $config;

    /** @param array<string, mixed> $config Configurações de imagem (ver Setting::imageConfig()) */
    public function __construct(array $config)
    {
        if (!extension_loaded('gd')) {
            throw new RuntimeException('A extensão GD do PHP não está disponível neste servidor.');
        }

        $this->config = $config;
    }

    /**
     * Processa um arquivo recebido em $_FILES e grava o resultado.
     *
     * @param array<string, mixed> $file  Item de $_FILES
     * @param string               $dir   Pasta de destino (absoluta)
     * @param string               $base  Nome do arquivo, sem extensão
     * @return array{path: string, width: int, height: int, bytes: int, format: string}
     */
    public function handleUpload(array $file, string $dir, string $base): array
    {
        $this->assertUploadOk($file);

        $tmp = (string) $file['tmp_name'];
        $info = @getimagesize($tmp);
        if ($info === false) {
            throw new RuntimeException('O arquivo enviado não é uma imagem válida.');
        }

        $sourceFormat = $this->formatFromType((int) $info[2]);
        if ($sourceFormat === null) {
            throw new RuntimeException('Formato de imagem não suportado. Envie ' . $this->allowedLabel() . '.');
        }
        if (!in_array($sourceFormat, $this->allowedFormats(), true)) {
            throw new RuntimeException('Formato não permitido. Envie ' . $this->allowedLabel() . '.');
        }

        $maxPixels = (int) $this->config['max_megapixels'] * 1_000_000;
        if ($info[0] * $info[1] > $maxPixels) {
            throw new RuntimeException(sprintf(
                'Imagem muito grande (%d × %d). O limite é de %d megapixels.',
                $info[0],
                $info[1],
                (int) $this->config['max_megapixels']
            ));
        }

        $image = $this->load($tmp, $sourceFormat);

        try {
            if ((bool) $this->config['auto_rotate'] && $sourceFormat === 'jpg' && function_exists('exif_read_data')) {
                $image = $this->applyExifRotation($image, $tmp);
            }

            $image = $this->resize($image, (int) $this->config['max_width'], (int) $this->config['max_height']);

            $outputFormat = (string) $this->config['output_format'];
            if ($outputFormat === 'auto') {
                // PNG costuma ter transparência; os demais viram o formato de origem.
                $outputFormat = $sourceFormat === 'png' ? 'png' : $sourceFormat;
            }

            [$binary, $quality] = $this->compress($image, $outputFormat);
            // compress() pode ter reduzido as dimensões; segue com a imagem final.
            $image = $this->lastImage ?? $image;

            if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
                throw new RuntimeException('Não foi possível criar a pasta de destino das imagens.');
            }
            if (!is_writable($dir)) {
                throw new RuntimeException('A pasta de imagens não tem permissão de escrita.');
            }

            $path = rtrim($dir, '/\\') . '/' . $base . '.' . $outputFormat;
            if (@file_put_contents($path, $binary) === false) {
                throw new RuntimeException('Não foi possível gravar a imagem processada.');
            }
            @chmod($path, 0644);

            return [
                'path' => $path,
                'width' => imagesx($image),
                'height' => imagesy($image),
                'bytes' => strlen($binary),
                'format' => $outputFormat,
                'quality' => $quality,
            ];
        } finally {
            imagedestroy($image);
        }
    }

    /** @param array<string, mixed> $file */
    private function assertUploadOk(array $file): void
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($error !== UPLOAD_ERR_OK) {
            throw new RuntimeException(match ($error) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'O arquivo excede o tamanho máximo aceito pelo servidor.',
                UPLOAD_ERR_PARTIAL => 'O envio foi interrompido. Tente novamente.',
                UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi selecionado.',
                UPLOAD_ERR_NO_TMP_DIR, UPLOAD_ERR_CANT_WRITE => 'O servidor não conseguiu gravar o arquivo temporário.',
                default => 'Falha no envio do arquivo.',
            });
        }

        if (!is_uploaded_file((string) $file['tmp_name'])) {
            throw new RuntimeException('Envio inválido.');
        }

        $maxBytes = (int) $this->config['max_upload_kb'] * 1024;
        if ((int) $file['size'] > $maxBytes) {
            throw new RuntimeException(sprintf(
                'Arquivo de %s. O limite de envio é de %d KB.',
                self::humanSize((int) $file['size']),
                (int) $this->config['max_upload_kb']
            ));
        }
    }

    private function load(string $path, string $format): GdImage
    {
        $image = match ($format) {
            'jpg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => @imagecreatefromwebp($path),
            default => false,
        };

        if ($image === false) {
            throw new RuntimeException('Não foi possível abrir a imagem enviada.');
        }

        return $image;
    }

    /** Corrige fotos de celular que chegam deitadas (tag EXIF Orientation). */
    private function applyExifRotation(GdImage $image, string $path): GdImage
    {
        $exif = @exif_read_data($path);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        if ($angle === 0) {
            return $image;
        }

        $rotated = @imagerotate($image, $angle, 0);
        if ($rotated === false) {
            return $image;
        }

        imagedestroy($image);

        return $rotated;
    }

    /** Reduz proporcionalmente; nunca amplia uma imagem pequena. */
    private function resize(GdImage $image, int $maxWidth, int $maxHeight): GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
        if ($ratio >= 1) {
            return $image;
        }

        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }

    /**
     * Comprime reduzindo a qualidade até caber no peso alvo.
     *
     * @return array{0: string, 1: int} Binário e qualidade utilizada
     */
    private function compress(GdImage $image, string $format): array
    {
        $targetBytes = (int) $this->config['target_kb'] * 1024;
        $quality = max(self::MIN_QUALITY, min(100, (int) $this->config['quality']));

        if ($format === 'png') {
            // PNG é sem perdas: só resta o nível de compressão (0–9).
            $binary = $this->encode($image, 'png', 9);
            if (strlen($binary) > $targetBytes && (bool) $this->config['png_to_jpg']) {
                // Acima do alvo, converte para JPEG sobre fundo branco.
                $flat = $this->flatten($image);
                imagedestroy($image);

                return $this->compress($flat, 'jpg');
            }

            $this->lastImage = $image;

            return [$binary, 9];
        }

        $binary = $this->encode($image, $format, $quality);

        while (strlen($binary) > $targetBytes && $quality > self::MIN_QUALITY) {
            $quality = max(self::MIN_QUALITY, $quality - 10);
            $binary = $this->encode($image, $format, $quality);
        }

        // Ainda acima do alvo na qualidade mínima: reduz as dimensões em 15% por vez.
        // Fotos muito detalhadas não cabem no peso só com compressão.
        $attempts = 0;
        while (strlen($binary) > $targetBytes && $attempts++ < 6 && imagesx($image) > 200) {
            $image = $this->resize(
                $image,
                (int) round(imagesx($image) * 0.85),
                (int) round(imagesy($image) * 0.85)
            );
            $binary = $this->encode($image, $format, $quality);
        }

        $this->lastImage = $image;

        return [$binary, $quality];
    }

    /** Imagem após a compressão (pode ter sido reduzida para caber no peso alvo). */
    private ?GdImage $lastImage = null;

    /** Achata a transparência sobre branco (necessário ao converter PNG em JPEG). */
    private function flatten(GdImage $image): GdImage
    {
        $flat = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($flat, 0, 0, imagecolorallocate($flat, 255, 255, 255));
        imagecopy($flat, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));

        return $flat;
    }

    private function encode(GdImage $image, string $format, int $quality): string
    {
        ob_start();
        match ($format) {
            'jpg' => imagejpeg($image, null, $quality),
            'webp' => imagewebp($image, null, $quality),
            'png' => (function () use ($image, $quality) {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                imagepng($image, null, min(9, $quality));
            })(),
            default => throw new RuntimeException('Formato de saída inválido: ' . $format),
        };

        return (string) ob_get_clean();
    }

    private function formatFromType(int $type): ?string
    {
        return match ($type) {
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_WEBP => 'webp',
            default => null,
        };
    }

    /** @return list<string> */
    private function allowedFormats(): array
    {
        $formats = array_values(array_intersect((array) $this->config['allowed_formats'], array_keys(self::FORMATS)));

        return $formats !== [] ? $formats : ['jpg'];
    }

    private function allowedLabel(): string
    {
        $labels = array_map(fn (string $f) => strtoupper($f), $this->allowedFormats());

        return implode(', ', $labels);
    }

    public static function humanSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1, ',', '.') . ' MB';
        }

        return number_format(max(1, (int) round($bytes / 1024)), 0, ',', '.') . ' KB';
    }

    /** Lista os formatos que a instalação do GD realmente suporta. */
    public static function supportedFormats(): array
    {
        if (!extension_loaded('gd')) {
            return [];
        }

        $info = gd_info();
        $supported = [];
        if (!empty($info['JPEG Support'])) {
            $supported[] = 'jpg';
        }
        if (!empty($info['WebP Support'])) {
            $supported[] = 'webp';
        }
        if (!empty($info['PNG Support'])) {
            $supported[] = 'png';
        }

        return $supported;
    }
}
