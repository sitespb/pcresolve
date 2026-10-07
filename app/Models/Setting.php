<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Database;

/**
 * Configurações da empresa (equivalente ao CompanySettings da versão React).
 * As chaves são as mesmas do objeto original (name, whatsapp, metaTitleDefault...).
 */
final class Setting
{
    /** Chaves editáveis pelo painel. */
    public const KEYS = [
        'name', 'corporateName', 'cnpj', 'phone', 'whatsapp', 'email', 'address',
        'neighborhood', 'city', 'state', 'zipCode', 'workingHoursWeekday', 'workingHoursSaturday',
        'aboutText', 'mission', 'warrantyTerm', 'ga4MeasurementId', 'gtmContainerId',
        'googleAdsConversionId', 'metaTitleDefault', 'metaDescriptionDefault', 'defaultWhatsappMessage',
        // Tratamento de imagens enviadas pelo painel (aba "Imagens & Upload")
        'imgAllowedFormats', 'imgOutputFormat', 'imgQuality', 'imgTargetKb', 'imgMaxUploadKb',
        'imgMaxWidth', 'imgMaxHeight', 'imgMaxMegapixels', 'imgAutoRotate', 'imgPngToJpg',
    ];

    /** Padrões do tratamento de imagens, usados quando a chave ainda não existe no banco. */
    public const IMAGE_DEFAULTS = [
        'imgAllowedFormats' => 'jpg,webp',
        'imgOutputFormat' => 'auto',
        'imgQuality' => '82',
        'imgTargetKb' => '100',
        'imgMaxUploadKb' => '8192',
        'imgMaxWidth' => '1200',
        'imgMaxHeight' => '1000',
        'imgMaxMegapixels' => '40',
        'imgAutoRotate' => '1',
        'imgPngToJpg' => '1',
    ];

    /** @var array<string, string>|null */
    private static ?array $cache = null;

    /** @return array<string, string> */
    public static function all(): array
    {
        if (self::$cache === null) {
            self::$cache = [];
            foreach (Database::select('SELECT setting_key, setting_value FROM settings') as $row) {
                self::$cache[(string) $row['setting_key']] = (string) $row['setting_value'];
            }
        }

        return self::$cache;
    }

    /** @param array<string, string> $values */
    public static function save(array $values): void
    {
        Database::transaction(function () use ($values): void {
            foreach ($values as $key => $value) {
                if (!in_array($key, self::KEYS, true)) {
                    continue;
                }
                Database::execute(
                    'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                     ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)',
                    [$key, $value]
                );
            }
        });

        self::$cache = null;
    }

    public static function flush(): void
    {
        self::$cache = null;
    }

    /** Valor de uma chave de imagem, já com o padrão aplicado. */
    public static function image(string $key): string
    {
        $all = self::all();
        $value = $all[$key] ?? '';

        return $value !== '' ? (string) $value : (self::IMAGE_DEFAULTS[$key] ?? '');
    }

    /**
     * Configuração normalizada para o ImageProcessor.
     *
     * @return array<string, mixed>
     */
    public static function imageConfig(): array
    {
        $formats = array_values(array_filter(
            array_map('trim', explode(',', self::image('imgAllowedFormats'))),
            fn (string $f) => $f !== ''
        ));

        $clamp = fn (string $key, int $min, int $max): int
            => max($min, min($max, (int) self::image($key)));

        return [
            'allowed_formats' => $formats !== [] ? $formats : ['jpg'],
            'output_format' => in_array(self::image('imgOutputFormat'), ['auto', 'jpg', 'webp', 'png'], true)
                ? self::image('imgOutputFormat')
                : 'auto',
            'quality' => $clamp('imgQuality', 40, 100),
            'target_kb' => $clamp('imgTargetKb', 20, 5000),
            'max_upload_kb' => $clamp('imgMaxUploadKb', 100, 51200),
            'max_width' => $clamp('imgMaxWidth', 100, 6000),
            'max_height' => $clamp('imgMaxHeight', 100, 6000),
            'max_megapixels' => $clamp('imgMaxMegapixels', 1, 200),
            'auto_rotate' => self::image('imgAutoRotate') === '1',
            'png_to_jpg' => self::image('imgPngToJpg') === '1',
        ];
    }
}
