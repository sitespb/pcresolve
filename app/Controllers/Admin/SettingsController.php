<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Setting;
use App\Support\Auth;
use App\Support\ImageProcessor;
use App\Support\Seeder;

final class SettingsController
{
    private const TABS = ['empresa', 'seo', 'whatsapp', 'imagens', 'privacidade'];

    public function index(): void
    {
        $tab = (string) input('aba', 'empresa');

        render('admin/settings', [
            'menu' => 'configuracoes',
            'pageTitle' => 'Configurações do Sistema | ' . setting('name'),
            'settings' => settings(),
            'tab' => in_array($tab, self::TABS, true) ? $tab : 'empresa',
            'allowReset' => (bool) config('app.allow_demo_reset'),
            'imageConfig' => Setting::imageConfig(),
            'supportedFormats' => ImageProcessor::supportedFormats(),
        ], 'admin');
    }

    public function update(): void
    {
        $tab = (string) input('tab', 'empresa');
        $tab = in_array($tab, self::TABS, true) ? $tab : 'empresa';
        $current = settings();

        $values = [];
        foreach (Setting::KEYS as $key) {
            if (array_key_exists($key, $_POST)) {
                $values[$key] = input_str($key, $key === 'aboutText' || $key === 'warrantyTerm' || $key === 'metaDescriptionDefault' ? 3000 : 255);
            }
        }

        // Aba "Imagens & Upload": formatos (seleção múltipla) e interruptores,
        // que não chegam no POST quando desmarcados.
        if ($tab === 'imagens') {
            $formats = array_values(array_intersect(
                (array) ($_POST['imgAllowedFormats'] ?? []),
                array_keys(ImageProcessor::FORMATS)
            ));
            if ($formats === []) {
                toast('Selecione ao menos um formato de imagem permitido.', 'warning');
                redirect('/painel/configuracoes?aba=imagens');
            }
            $values['imgAllowedFormats'] = implode(',', $formats);
            $values['imgAutoRotate'] = input('imgAutoRotate') !== null ? '1' : '0';
            $values['imgPngToJpg'] = input('imgPngToJpg') !== null ? '1' : '0';

            // Limites numéricos: mantém dentro de faixas seguras.
            foreach ([
                'imgQuality' => [40, 100],
                'imgTargetKb' => [20, 5000],
                'imgMaxUploadKb' => [100, 51200],
                'imgMaxWidth' => [100, 6000],
                'imgMaxHeight' => [100, 6000],
                'imgMaxMegapixels' => [1, 200],
            ] as $key => [$min, $max]) {
                if (array_key_exists($key, $values)) {
                    $values[$key] = (string) max($min, min($max, (int) $values[$key]));
                }
            }
        }

        // Campo combinado "Bairro / Cidade" no formato "Manaíra - João Pessoa/PB".
        if (array_key_exists('locality', $_POST)) {
            $locality = input_str('locality', 255);
            if (preg_match('/^(.*?)\s+-\s+(.+?)\s*\/\s*([A-Za-z]{2})$/u', $locality, $m)) {
                $values['neighborhood'] = trim($m[1]);
                $values['city'] = trim($m[2]);
                $values['state'] = strtoupper($m[3]);
            } elseif ($locality !== '') {
                $values['neighborhood'] = $locality;
            }
        }

        foreach (['name' => 'Nome Fantasia', 'phone' => 'Telefone Fixo', 'whatsapp' => 'WhatsApp Comercial'] as $key => $label) {
            if (array_key_exists($key, $values) && $values[$key] === '') {
                toast("O campo \"$label\" é obrigatório.", 'warning');
                redirect('/painel/configuracoes?aba=' . $tab);
            }
        }

        Setting::save(array_merge($current, $values));

        toast('Configurações da empresa salvas com sucesso!');
        redirect('/painel/configuracoes?aba=' . $tab);
    }

    public function reset(): void
    {
        if (!config('app.allow_demo_reset')) {
            toast('A restauração de dados está desativada neste ambiente (ALLOW_DEMO_RESET=false).', 'error');
            redirect('/painel/configuracoes');
        }

        Seeder::resetDemo(Auth::id());
        toast('Dados restaurados para o padrão de demonstração.', 'info');
        redirect('/painel/configuracoes');
    }
}
