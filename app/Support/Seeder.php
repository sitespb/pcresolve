<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;

/** Cria as tabelas e popula os dados de demonstração. */
final class Seeder
{
    /** Tabelas de conteúdo restauradas pelo botão "Restaurar Padrão de Demonstração". */
    private const CONTENT_TABLES = ['services', 'leads', 'testimonials', 'faqs', 'cities', 'settings', 'analytics_snapshots'];

    public static function migrate(): void
    {
        $sql = (string) file_get_contents(BASE_PATH . '/database/schema.sql');
        // Remove comentários de linha e executa instrução por instrução.
        $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? '';
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
            Database::pdo()->exec($statement);
        }
    }

    /** @return array<string, mixed> */
    public static function data(): array
    {
        return require BASE_PATH . '/database/seed-data.php';
    }

    /**
     * Popula apenas as tabelas vazias (seguro para rodar várias vezes).
     *
     * @return list<string> Tabelas populadas
     */
    public static function seedMissing(): array
    {
        $seeded = [];
        foreach (self::CONTENT_TABLES as $table) {
            $count = (int) Database::value("SELECT COUNT(*) FROM `$table`");
            if ($count === 0) {
                self::seedTable($table, self::data());
                $seeded[] = $table;
            }
        }
        Setting::flush();

        return $seeded;
    }

    /** Apaga e recria todo o conteúdo do site (não altera e-mail/senha dos usuários). */
    public static function resetDemo(?int $userId = null): void
    {
        $data = self::data();

        Database::transaction(function () use ($data, $userId): void {
            foreach (self::CONTENT_TABLES as $table) {
                Database::execute("DELETE FROM `$table`");
                self::seedTable($table, $data);
            }

            if ($userId !== null) {
                $user = $data['user'];
                Database::update('users', [
                    'name' => $user['name'],
                    'phone' => $user['phone'],
                    'role' => $user['role'],
                    'avatar' => $user['avatar'],
                    'department' => $user['department'],
                    'notify_email_new_lead' => $user['notify_email_new_lead'],
                    'notify_whatsapp_alerts' => $user['notify_whatsapp_alerts'],
                    'notify_browser_sound' => $user['notify_browser_sound'],
                    'updated_at' => now(),
                ], ['id' => $userId]);
            }
        });

        Setting::flush();
    }

    /** @param array<string, mixed> $data */
    private static function seedTable(string $table, array $data): void
    {
        $now = now();

        switch ($table) {
            case 'settings':
                foreach ($data['settings'] as $key => $value) {
                    Database::insert('settings', ['setting_key' => $key, 'setting_value' => $value]);
                }
                break;

            case 'services':
                foreach ($data['services'] as $i => $service) {
                    Database::insert('services', array_merge($service, [
                        'highlights' => json_encode($service['highlights'], JSON_UNESCAPED_UNICODE),
                        'recommended_for' => json_encode($service['recommended_for'], JSON_UNESCAPED_UNICODE),
                        'active' => 1,
                        'sort_order' => $i + 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]));
                }
                break;

            case 'leads':
                // Inserção em ordem inversa: o mais recente fica com o maior id.
                foreach (array_reverse($data['leads']) as $lead) {
                    Database::insert('leads', array_merge($lead, ['source' => 'site', 'ip_address' => null]));
                }
                break;

            case 'testimonials':
                foreach (array_reverse($data['testimonials']) as $item) {
                    Database::insert('testimonials', array_merge($item, ['created_at' => $now]));
                }
                break;

            case 'faqs':
                foreach ($data['faqs'] as $i => $faq) {
                    Database::insert('faqs', array_merge($faq, ['sort_order' => $i + 1]));
                }
                break;

            case 'cities':
                foreach ($data['cities'] as $i => $city) {
                    Database::insert('cities', array_merge($city, ['active' => 1, 'sort_order' => $i + 1]));
                }
                break;

            case 'analytics_snapshots':
                foreach ($data['analytics'] as $timeframe => $payload) {
                    Database::insert('analytics_snapshots', [
                        'timeframe' => $timeframe,
                        'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                    ]);
                }
                break;
        }
    }
}
