<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Database;

/**
 * Relatórios analíticos por período (mesma estrutura de ANALYTICS_DATA da versão React).
 */
final class Analytics
{
    public const TIMEFRAMES = [
        'hoje' => 'Hoje',
        '7dias' => 'Últimos 7 dias',
        '30dias' => 'Últimos 30 dias',
        'mes' => 'Mês Atual',
    ];

    public static function normalize(?string $timeframe): string
    {
        return array_key_exists((string) $timeframe, self::TIMEFRAMES) ? (string) $timeframe : '7dias';
    }

    /** @return array<string, mixed> */
    public static function get(string $timeframe): array
    {
        $json = Database::value('SELECT payload FROM analytics_snapshots WHERE timeframe = ?', [self::normalize($timeframe)]);
        $data = is_string($json) ? json_decode($json, true) : null;

        return is_array($data) ? $data : self::empty();
    }

    /** @return array<string, mixed> */
    private static function empty(): array
    {
        return [
            'summary' => [
                'totalVisits' => 0, 'uniqueVisitors' => 0, 'pageViews' => 0, 'avgTimeOnSite' => '0s',
                'bounceRate' => '0%', 'conversionRate' => '0%', 'totalLeads' => 0, 'whatsappClicks' => 0, 'phoneCalls' => 0,
            ],
            'dailyVisits' => [],
            'trafficSources' => [],
            'cityStats' => [],
            'deviceBreakdown' => [],
            'topPages' => [],
            'conversionFunnel' => [],
        ];
    }
}
