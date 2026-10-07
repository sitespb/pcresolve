<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Database;

final class Service
{
    /** @return list<array<string, mixed>> */
    public static function all(): array
    {
        return array_map([self::class, 'hydrate'], Database::select('SELECT * FROM services ORDER BY sort_order, id'));
    }

    /** @return list<array<string, mixed>> */
    public static function active(?int $limit = null): array
    {
        $sql = 'SELECT * FROM services WHERE active = 1 ORDER BY sort_order, id';
        if ($limit !== null) {
            $sql .= ' LIMIT ' . max(1, $limit);
        }

        return array_map([self::class, 'hydrate'], Database::select($sql));
    }

    public static function countActive(): int
    {
        return (int) Database::value('SELECT COUNT(*) FROM services WHERE active = 1');
    }

    /** @return array<string, mixed>|null */
    public static function find(int $id): ?array
    {
        $row = Database::first('SELECT * FROM services WHERE id = ?', [$id]);

        return $row ? self::hydrate($row) : null;
    }

    /** @return array<string, mixed>|null */
    public static function findActiveBySlug(string $slug): ?array
    {
        $row = Database::first('SELECT * FROM services WHERE slug = ? AND active = 1', [$slug]);

        return $row ? self::hydrate($row) : null;
    }

    /** @param array<string, mixed> $data */
    public static function create(array $data): int
    {
        $data['slug'] = self::uniqueSlug((string) $data['slug']);
        $data['highlights'] = json_encode($data['highlights'] ?? [], JSON_UNESCAPED_UNICODE);
        $data['recommended_for'] = json_encode($data['recommended_for'] ?? [], JSON_UNESCAPED_UNICODE);
        $data['sort_order'] ??= ((int) Database::value('SELECT COALESCE(MAX(sort_order), 0) FROM services')) + 1;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        return Database::insert('services', $data);
    }

    /** @param array<string, mixed> $data */
    public static function update(int $id, array $data): void
    {
        $data['updated_at'] = now();
        Database::update('services', $data, ['id' => $id]);
    }

    public static function toggle(int $id): ?bool
    {
        $service = self::find($id);
        if ($service === null) {
            return null;
        }
        $next = !$service['active'];
        self::update($id, ['active' => $next ? 1 : 0]);

        return $next;
    }

    public static function delete(int $id): void
    {
        Database::execute('DELETE FROM services WHERE id = ?', [$id]);
    }

    private static function uniqueSlug(string $slug): string
    {
        $base = $slug !== '' ? $slug : 'servico';
        $candidate = $base;
        $i = 2;
        while (Database::value('SELECT 1 FROM services WHERE slug = ?', [$candidate])) {
            $candidate = $base . '-' . $i++;
        }

        return $candidate;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private static function hydrate(array $row): array
    {
        $row['id'] = (int) $row['id'];
        $row['active'] = (bool) $row['active'];
        $row['price_starting_at'] = (float) $row['price_starting_at'];
        $row['warranty_days'] = (int) $row['warranty_days'];
        $row['highlights'] = json_decode((string) ($row['highlights'] ?? '[]'), true) ?: [];
        $row['recommended_for'] = json_decode((string) ($row['recommended_for'] ?? '[]'), true) ?: [];

        return $row;
    }
}
