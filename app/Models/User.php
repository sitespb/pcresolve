<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Database;

final class User
{
    public const ROLES = [
        'superadmin' => 'Superadministrador',
        'tecnico' => 'Técnico de Bancada',
        'atendente' => 'Atendente de Balcão',
    ];

    public static function find(int $id): ?array
    {
        $row = Database::first('SELECT * FROM users WHERE id = ?', [$id]);

        return $row ? self::hydrate($row) : null;
    }

    public static function findByEmail(string $email): ?array
    {
        $row = Database::first('SELECT * FROM users WHERE email = ?', [mb_strtolower(trim($email))]);

        return $row ? self::hydrate($row) : null;
    }

    public static function emailTaken(string $email, int $exceptId): bool
    {
        return Database::value('SELECT 1 FROM users WHERE email = ? AND id <> ?', [mb_strtolower($email), $exceptId]) !== null;
    }

    /** @param array<string, mixed> $data */
    public static function create(array $data, string $password): int
    {
        return Database::insert('users', [
            'name' => $data['name'],
            'email' => mb_strtolower((string) $data['email']),
            'phone' => $data['phone'] ?? '',
            'role' => $data['role'] ?? 'superadmin',
            'avatar' => $data['avatar'] ?? '',
            'department' => $data['department'] ?? '',
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'notify_email_new_lead' => (int) ($data['notify_email_new_lead'] ?? 1),
            'notify_whatsapp_alerts' => (int) ($data['notify_whatsapp_alerts'] ?? 1),
            'notify_browser_sound' => (int) ($data['notify_browser_sound'] ?? 1),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** @param array<string, mixed> $data */
    public static function updateProfile(int $id, array $data): void
    {
        $data['updated_at'] = now();
        Database::update('users', $data, ['id' => $id]);
    }

    public static function setPassword(int $id, string $password): void
    {
        Database::update('users', [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'updated_at' => now(),
        ], ['id' => $id]);
    }

    public static function recordLogin(int $id, string $ip, string $userAgent, string $sessionId): void
    {
        Database::update('users', ['last_login_at' => now()], ['id' => $id]);
        Database::insert('user_logins', [
            'user_id' => $id,
            'ip_address' => $ip,
            'user_agent' => mb_substr($userAgent, 0, 255),
            'session_hash' => hash('sha256', $sessionId),
            'created_at' => now(),
        ]);
        // Mantém apenas o histórico recente.
        Database::execute(
            'DELETE FROM user_logins WHERE user_id = ? AND id NOT IN (SELECT id FROM (SELECT id FROM user_logins WHERE user_id = ? ORDER BY id DESC LIMIT 20) recent)',
            [$id, $id]
        );
    }

    /** @return list<array<string, mixed>> */
    public static function recentLogins(int $id, int $limit = 3): array
    {
        return Database::select(
            'SELECT * FROM user_logins WHERE user_id = ? ORDER BY id DESC LIMIT ' . max(1, $limit),
            [$id]
        );
    }

    /** "Hoje às 15:42 (Chrome / macOS)" — mesmo formato exibido na versão React. */
    public static function describeLogin(?string $datetime, ?string $userAgent): string
    {
        if (!$datetime) {
            return 'Primeiro acesso';
        }

        $ts = strtotime($datetime) ?: time();
        $day = date('Y-m-d', $ts);
        $label = match (true) {
            $day === date('Y-m-d') => 'Hoje às ' . date('H:i', $ts),
            $day === date('Y-m-d', strtotime('-1 day')) => 'Ontem às ' . date('H:i', $ts),
            default => date('d/m/Y \à\s H:i', $ts),
        };

        if ($userAgent) {
            $device = self::parseUserAgent($userAgent);
            $label .= ' (' . $device['browser'] . ' / ' . $device['os'] . ')';
        }

        return $label;
    }

    /** @return array{browser: string, os: string, device: string, mobile: bool} */
    public static function parseUserAgent(string $ua): array
    {
        $browser = match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'SamsungBrowser') => 'Samsung Internet',
            str_contains($ua, 'Chrome/') => 'Chrome',
            str_contains($ua, 'Safari/') => 'Safari',
            default => 'Navegador',
        };

        $os = match (true) {
            str_contains($ua, 'iPhone') => 'iOS',
            str_contains($ua, 'iPad') => 'iPadOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Mac OS X') || str_contains($ua, 'Macintosh') => 'macOS',
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Sistema desconhecido',
        };

        $mobile = (bool) preg_match('/iPhone|Android.+Mobile|Mobile Safari|Windows Phone/i', $ua);

        $device = match (true) {
            str_contains($ua, 'iPhone') => 'iPhone',
            str_contains($ua, 'iPad') => 'iPad',
            str_contains($ua, 'Android') => $mobile ? 'Smartphone Android' : 'Tablet Android',
            $os === 'macOS' => 'Apple Mac',
            $os === 'Windows' => 'Computador Windows',
            $os === 'Linux' => 'Computador Linux',
            default => 'Dispositivo',
        };

        return ['browser' => $browser, 'os' => $os, 'device' => $device . ' · ' . $browser, 'mobile' => $mobile];
    }

    private static function hydrate(array $row): array
    {
        $row['id'] = (int) $row['id'];
        $row['notify_email_new_lead'] = (bool) $row['notify_email_new_lead'];
        $row['notify_whatsapp_alerts'] = (bool) $row['notify_whatsapp_alerts'];
        $row['notify_browser_sound'] = (bool) $row['notify_browser_sound'];
        $row['role_label'] = self::ROLES[$row['role']] ?? 'Técnico';

        return $row;
    }
}
