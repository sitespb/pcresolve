<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Analytics;
use App\Models\Lead;
use App\Models\Service;

final class DashboardController
{
    public function index(): void
    {
        $counts = Lead::countsByStatus();

        $recent = [];
        foreach (['todos', 'pendente', 'em_execucao', 'concluido'] as $filter) {
            $recent[$filter] = Lead::recent($filter === 'todos' ? null : $filter, 5);
        }

        render('admin/dashboard', [
            'menu' => 'dashboard',
            'pageTitle' => 'Painel de Controle Técnico | ' . setting('name'),
            'pendingCount' => $counts['pendente'],
            'inProgressCount' => $counts['em_diagnostico'] + $counts['em_execucao'],
            'completedCount' => $counts['concluido'] + $counts['entregue'],
            'totalBudget' => Lead::totalBudget(),
            'recent' => $recent,
            'analytics' => Analytics::get('7dias'),
            'activeServices' => Service::countActive(),
        ], 'admin');
    }
}
