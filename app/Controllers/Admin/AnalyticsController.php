<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Analytics;

final class AnalyticsController
{
    public function index(): void
    {
        $timeframe = Analytics::normalize((string) input('periodo', '7dias'));

        render('admin/analytics', [
            'menu' => 'analytics',
            'pageTitle' => 'Relatórios Analíticos | ' . setting('name'),
            'timeframe' => $timeframe,
            'analytics' => Analytics::get($timeframe),
        ], 'admin');
    }

    /** Mesmo CSV gerado pelo botão "Exportar CSV" da versão React. */
    public function export(): void
    {
        $timeframe = Analytics::normalize((string) input('periodo', '7dias'));
        $data = Analytics::get($timeframe);

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="pcresolve_relatorio_analytics_' . $timeframe . '.csv"');

        // BOM para o Excel reconhecer acentos (Terça, Sábado...).
        echo "\xEF\xBB\xBF";
        echo "Data/Periodo,Visitantes,Pageviews,Leads\n";
        foreach ($data['dailyVisits'] as $row) {
            echo '"' . str_replace('"', '""', (string) $row['date']) . '",'
                . (int) $row['visitors'] . ','
                . (int) $row['pageViews'] . ','
                . (int) $row['leads'] . "\n";
        }
    }
}
