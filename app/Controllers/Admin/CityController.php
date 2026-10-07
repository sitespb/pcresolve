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
}
