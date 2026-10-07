<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\City;

final class CityController
{
    public function index(): void
    {
        render('admin/cities', [
            'menu' => 'cidades',
            'pageTitle' => 'Cidades & Leva e Traz | ' . setting('name'),
            'cities' => City::all(),
        ], 'admin');
    }

    public function toggle(string $id): void
    {
        if (City::toggle((int) $id)) {
            toast('Área de cobertura atualizada.');
        }
        redirect('/painel/cidades');
    }

    public function fee(string $id): void
    {
        if (City::find((int) $id) !== null) {
            City::updateFee((int) $id, input_money('delivery_fee') ?? 0);
            toast('Taxa de Leva e Traz ajustada.');
        }
        redirect('/painel/cidades');
    }

    public function store(): void
    {
        $name = input_str('name', 120);
        if ($name === '') {
            with_old($_POST);
            toast('Informe o nome do município.', 'warning');
            redirect('/painel/cidades?novo=1');
        }

        City::create([
            'name' => $name,
            'coverage' => input_str('coverage', 2000),
            'delivery_available' => input('delivery_available') !== null,
            'delivery_fee' => input_money('delivery_fee') ?? 0,
            'notes' => input_str('notes', 2000),
        ]);

        toast('Cidade "' . $name . '" adicionada à cobertura.');
        redirect('/painel/cidades');
    }

    public function update(string $id): void
    {
        $city = City::find((int) $id);
        if ($city === null) {
            toast('Cidade não encontrada.', 'error');
            redirect('/painel/cidades');
        }

        $name = input_str('name', 120);
        if ($name === '') {
            toast('Informe o nome do município.', 'warning');
            redirect('/painel/cidades');
        }

        City::update((int) $id, [
            'name' => $name,
            'coverage' => input_str('coverage', 2000),
            'delivery_available' => input('delivery_available') !== null,
            'delivery_fee' => input_money('delivery_fee') ?? 0,
        ]);

        toast('Cidade atualizada com sucesso!');
        redirect('/painel/cidades');
    }

    public function destroy(string $id): void
    {
        $city = City::find((int) $id);
        if ($city === null) {
            toast('Cidade não encontrada.', 'error');
            redirect('/painel/cidades');
        }

        City::delete((int) $id);
        toast('Cidade "' . $city['name'] . '" removida da cobertura.', 'info');
        redirect('/painel/cidades');
    }
}
