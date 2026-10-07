<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Database;

/** Cidades atendidas e taxas do Leva e Traz (CityArea na versão React). */
final class City
{
    public static function all(): array
    {
        return array_map([self::class, 'hydrate'], Database::select('SELECT * FROM cities ORDER BY sort_order, id'));
    }

    /** Apenas cidades ativas aparecem no site público. */
    public static function active(): array
    {
        return array_map([self::class, 'hydrate'], Database::select('SELECT * FROM cities WHERE active = 1 ORDER BY sort_order, id'));
    }

    public static function find(int $id): ?array
    {
        $row = Database::first('SELECT * FROM cities WHERE id = ?', [$id]);

        return $row ? self::hydrate($row) : null;
    }

    public static function toggle(int $id): bool
    {
        return Database::execute('UPDATE cities SET active = 1 - active WHERE id = ?', [$id]) > 0;
    }

    public static function updateFee(int $id, float $fee): void
    {
        Database::update('cities', ['delivery_fee' => max(0, $fee)], ['id' => $id]);
    }

    /** @param array<string, mixed> $data */
    public static function create(array $data): int
    {
        return Database::insert('cities', [
            'name' => $data['name'],
            'coverage' => $data['coverage'],
            'delivery_available' => !empty($data['delivery_available']) ? 1 : 0,
            'delivery_fee' => max(0, (float) $data['delivery_fee']),
            'notes' => $data['notes'] ?? '',
            'active' => 1,
            'sort_order' => ((int) Database::value('SELECT COALESCE(MAX(sort_order), 0) FROM cities')) + 1,
        ]);
    }

    /** @param array<string, mixed> $data */
    public static function update(int $id, array $data): bool
    {
        if (Database::value('SELECT 1 FROM cities WHERE id = ?', [$id]) === null) {
            return false;
        }

        Database::update('cities', [
            'name' => $data['name'],
            'coverage' => $data['coverage'],
            'delivery_available' => !empty($data['delivery_available']) ? 1 : 0,
            'delivery_fee' => max(0, (float) $data['delivery_fee']),
        ], ['id' => $id]);

        return true;
    }

    public static function delete(int $id): void
    {
        Database::execute('DELETE FROM cities WHERE id = ?', [$id]);
    }

    private static function hydrate(array $row): array
    {
        $row['id'] = (int) $row['id'];
        $row['active'] = (bool) $row['active'];
        $row['delivery_available'] = (bool) $row['delivery_available'];
        $row['delivery_fee'] = (float) $row['delivery_fee'];

        return $row;
    }
}
