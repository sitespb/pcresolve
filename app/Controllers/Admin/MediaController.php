<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Setting;
use App\Support\ImageProcessor;
use App\Support\MediaLibrary;
use Throwable;

/** Biblioteca de mídia: inventário, envio e remoção das imagens do site. */
final class MediaController
{
    private const VIEWS = ['miniaturas', 'lista'];

    public function index(): void
    {
        // A lista vai inteira para a tela; a troca de visão, o filtro e a busca
        // acontecem no navegador (Alpine), sem recarregar. Os parâmetros da URL
        // servem só como estado inicial — filtrar nos dois lados criaria duas
        // fontes de verdade para a mesma coisa.
        $itens = MediaLibrary::items();
        $visao = (string) input('visao', 'miniaturas');
        $filtro = (string) input('filtro', 'todas');

        render('admin/media', [
            'menu' => 'biblioteca',
            'pageTitle' => 'Biblioteca de Mídia | ' . setting('name'),
            'items' => $itens,
            'stats' => MediaLibrary::stats($itens),
            'view' => in_array($visao, self::VIEWS, true) ? $visao : 'miniaturas',
            'filter' => in_array($filtro, ['todas', 'orfas', 'em-uso'], true) ? $filtro : 'todas',
            'imageConfig' => Setting::imageConfig(),
            'uploadHint' => self::uploadHint(),
        ], 'admin');
    }

    /** Envio de uma ou mais imagens para a biblioteca. */
    public function store(): void
    {
        $arquivos = self::normalizeFiles($_FILES['images'] ?? null);

        if ($arquivos === []) {
            toast('Selecione ao menos uma imagem para enviar.', 'warning');
            redirect('/painel/biblioteca');
        }

        $enviadas = 0;
        $erros = [];
        foreach ($arquivos as $arquivo) {
            try {
                $item = MediaLibrary::store($arquivo);
                $enviadas++;
                if (count($arquivos) === 1) {
                    toast(sprintf(
                        'Imagem enviada! Otimizada para %s px e %s.',
                        $item['dimensions'],
                        $item['size_label']
                    ), 'success');
                }
            } catch (Throwable $e) {
                $erros[] = ($arquivo['name'] ?? 'arquivo') . ': ' . $e->getMessage();
            }
        }

        if ($enviadas > 1) {
            toast("$enviadas imagens enviadas para a biblioteca.", 'success');
        }
        foreach (array_slice($erros, 0, 3) as $erro) {
            toast($erro, 'error');
        }

        redirect('/painel/biblioteca');
    }

    /** Remoção definitiva do arquivo (só quando não está em uso). */
    public function destroy(): void
    {
        $caminho = input_str('path', 500);

        try {
            MediaLibrary::delete($caminho);
        } catch (Throwable $e) {
            toast($e->getMessage(), 'error');
            redirect(previous_url('/painel/biblioteca'));
        }

        toast('Imagem removida da biblioteca.', 'info');
        redirect(previous_url('/painel/biblioteca'));
    }

    /**
     * Transforma o $_FILES de um campo múltiplo (images[]) em uma lista de
     * arquivos no formato que o ImageProcessor espera.
     *
     * @return list<array<string, mixed>>
     */
    private static function normalizeFiles(mixed $campo): array
    {
        if (!is_array($campo) || !isset($campo['name'])) {
            return [];
        }

        // Campo simples (name é string).
        if (!is_array($campo['name'])) {
            return (int) ($campo['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE ? [] : [$campo];
        }

        $lista = [];
        foreach (array_keys($campo['name']) as $i) {
            if ((int) ($campo['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $lista[] = [
                'name' => $campo['name'][$i],
                'type' => $campo['type'][$i] ?? '',
                'tmp_name' => $campo['tmp_name'][$i] ?? '',
                'error' => $campo['error'][$i] ?? UPLOAD_ERR_NO_FILE,
                'size' => $campo['size'][$i] ?? 0,
            ];
        }

        return $lista;
    }

    /**
     * Imagens disponíveis para o seletor embutido em outras telas (serviços, etc.).
     *
     * @return list<array<string, mixed>>
     */
    public static function pickerItems(): array
    {
        return array_map(static fn (array $i): array => [
            'path' => $i['path'],
            'url' => $i['url'],
            'filename' => $i['filename'],
            'dimensions' => $i['dimensions'],
            'size_label' => $i['size_label'],
            'usage_count' => $i['usage_count'],
        ], MediaLibrary::items());
    }

    /** Valor do atributo accept dos campos de arquivo, conforme os formatos liberados. */
    public static function acceptAttr(): string
    {
        return implode(',', array_map(
            static fn (string $f): string => $f === 'jpg' ? 'image/jpeg' : 'image/' . $f,
            (array) Setting::imageConfig()['allowed_formats']
        ));
    }

    /** Resumo textual do limite de envio, usado nas dicas de interface. */
    public static function uploadHint(): string
    {
        $cfg = Setting::imageConfig();
        $formatos = implode(', ', array_map('strtoupper', (array) $cfg['allowed_formats']));
        $limite = (int) $cfg['max_upload_kb'] >= 1024
            ? round((int) $cfg['max_upload_kb'] / 1024, 1) . ' MB'
            : (int) $cfg['max_upload_kb'] . ' KB';

        return sprintf(
            '%s · até %s · otimizada para %d × %d px e %s',
            $formatos,
            $limite,
            (int) $cfg['max_width'],
            (int) $cfg['max_height'],
            ImageProcessor::humanSize((int) $cfg['target_kb'] * 1024)
        );
    }
}
