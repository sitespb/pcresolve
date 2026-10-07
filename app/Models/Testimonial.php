<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Database;

final class Testimonial
{
    /** Mais recentes primeiro (na versão React os novos entravam no início da lista). */
    public static function all(): array
    {
        return array_map([self::class, 'hydrate'], Database::select('SELECT * FROM testimonials ORDER BY id DESC'));
    }

    public static function approved(int $limit): array
    {
        return array_map(
            [self::class, 'hydrate'],
            Database::select('SELECT * FROM testimonials WHERE approved = 1 ORDER BY id DESC LIMIT ' . max(1, $limit))
        );
    }

    /** @param array<string, mixed> $data */
    public static function create(array $data): int
    {
        return Database::insert('testimonials', [
            'author' => $data['author'],
            'location' => $data['location'],
            'rating' => $data['rating'],
            'text' => $data['text'],
            'service_title' => $data['service_title'],
            'testimonial_date' => date('Y-m-d'),
            'verified' => 1,
            'approved' => 0, // Publicação somente após aprovação (requisito do PRD).
            'created_at' => now(),
        ]);
    }

    public static function setApproved(int $id, bool $approved): bool
    {
        if (Database::value('SELECT 1 FROM testimonials WHERE id = ?', [$id]) === null) {
            return false;
        }
        Database::update('testimonials', ['approved' => $approved ? 1 : 0], ['id' => $id]);

        return true;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private static function hydrate(array $row): array
    {
        $row['id'] = (int) $row['id'];
        $row['rating'] = max(1, min(5, (int) $row['rating']));
        $row['approved'] = (bool) $row['approved'];
        $row['verified'] = (bool) $row['verified'];
        $row['date_label'] = format_date_br((string) $row['testimonial_date']);

        return $row;
    }
}
