<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Service;
use App\Support\MediaLibrary;
use Throwable;

final class ServiceController
{
    private const CATEGORIES = ['hardware', 'software', 'preventiva', 'corporativo'];

    /** Imagem usada quando o cadastro é salvo sem escolher nenhuma. */
    private const DEFAULT_IMAGE = 'assets/img/pasta-termica.jpg';

    public function index(): void
    {
        render('admin/services', [
            'menu' => 'servicos',
            'pageTitle' => 'Catálogo de Serviços | ' . setting('name'),
            'services' => Service::all(),
            // Acervo para o seletor "escolher da biblioteca" dentro dos modais.
            'media' => MediaController::pickerItems(),
            'uploadHint' => MediaController::uploadHint(),
            'defaultImage' => self::DEFAULT_IMAGE,
        ], 'admin');
    }

    public function store(): void
    {
        $title = input_str('title', 190);
        if ($title === '') {
            with_old($_POST);
            toast('Título do serviço é obrigatório.', 'warning');
            redirect('/painel/servicos?novo=1');
        }

        try {
            $image = MediaLibrary::resolveChoice('image_file', 'image_path', self::DEFAULT_IMAGE);
        } catch (Throwable $e) {
            with_old($_POST);
            toast($e->getMessage(), 'error');
            redirect('/painel/servicos?novo=1');
        }

        $category = input_str('category', 20);
        $shortDesc = input_str('short_desc', 2000);

        Service::create([
            'slug' => slugify($title),
            'title' => $title,
            'short_desc' => $shortDesc !== '' ? $shortDesc : $title,
            'full_desc' => $shortDesc !== '' ? $shortDesc : $title,
            'category' => in_array($category, self::CATEGORIES, true) ? $category : 'hardware',
            'price_starting_at' => max(0, input_money('price_starting_at') ?? 0),
            'turnaround_time' => input_str('turnaround_time', 100),
            'warranty_days' => 90,
            'icon_name' => 'Wrench',
            'image' => $image !== '' ? $image : self::DEFAULT_IMAGE,
            'highlights' => ['Testes laboratoriais completos', 'Garantia de 90 dias'],
            'recommended_for' => ['Manutenção preventiva e corretiva'],
            'active' => 1,
        ]);

        toast('Serviço "' . $title . '" cadastrado com sucesso!');
        redirect('/painel/servicos');
    }

    public function update(string $id): void
    {
        $service = Service::find((int) $id);
        if ($service === null) {
            toast('Serviço não encontrado.', 'error');
            redirect('/painel/servicos');
        }

        $title = input_str('title', 190);
        if ($title === '') {
            toast('Título do serviço é obrigatório.', 'warning');
            redirect('/painel/servicos');
        }

        $category = input_str('category', 20);

        try {
            $image = MediaLibrary::resolveChoice('image_file', 'image_path', (string) $service['image']);
        } catch (Throwable $e) {
            toast($e->getMessage(), 'error');
            redirect('/painel/servicos');
        }

        Service::update((int) $id, [
            'title' => $title,
            'short_desc' => input_str('short_desc', 2000),
            'category' => in_array($category, self::CATEGORIES, true) ? $category : $service['category'],
            'price_starting_at' => max(0, input_money('price_starting_at') ?? 0),
            'turnaround_time' => input_str('turnaround_time', 100),
            'image' => $image,
        ]);

        toast('Serviço atualizado com sucesso!');
        redirect('/painel/servicos');
    }

    public function toggle(string $id): void
    {
        $next = Service::toggle((int) $id);
        if ($next !== null) {
            toast('Serviço ' . ($next ? 'ativado' : 'desativado') . ' no catálogo público.');
        }
        redirect('/painel/servicos');
    }

    public function destroy(string $id): void
    {
        $service = Service::find((int) $id);
        if ($service === null) {
            toast('Serviço não encontrado.', 'error');
            redirect('/painel/servicos');
        }

        Service::delete((int) $id);
        // A imagem continua na biblioteca de propósito: ela pode estar em uso em
        // outro cadastro, e a remoção de arquivos é feita na Biblioteca de Mídia.
        toast('Serviço "' . $service['title'] . '" removido com sucesso.', 'info');
        redirect('/painel/servicos');
    }
}
