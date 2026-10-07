<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Service;

final class ServiceController
{
    private const CATEGORIES = ['hardware', 'software', 'preventiva', 'corporativo'];

    public function index(): void
    {
        render('admin/services', [
            'menu' => 'servicos',
            'pageTitle' => 'Catálogo de Serviços | ' . setting('name'),
            'services' => Service::all(),
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
            'image' => 'assets/img/pasta-termica.jpg',
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

        Service::update((int) $id, [
            'title' => $title,
            'short_desc' => input_str('short_desc', 2000),
            'price_starting_at' => max(0, input_money('price_starting_at') ?? 0),
            'turnaround_time' => input_str('turnaround_time', 100),
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
        Service::delete((int) $id);
        toast('Serviço removido com sucesso.', 'info');
        redirect('/painel/servicos');
    }
}
