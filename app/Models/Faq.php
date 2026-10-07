<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Database;

final class Faq
{
    public static function all(): array
    {
        return array_map(function (array $row): array {
            $row['id'] = (int) $row['id'];

            return $row;
        }, Database::select('SELECT * FROM faqs ORDER BY sort_order, id'));
    }

    public static function create(string $question, string $answer, string $category): int
    {
        $order = (int) Database::value('SELECT COUNT(*) FROM faqs') + 1;

        return Database::insert('faqs', [
            'question' => $question,
            'answer' => $answer,
            'category' => $category,
            'sort_order' => $order,
        ]);
    }

    public static function update(int $id, string $question, string $answer, string $category): bool
    {
        if (Database::value('SELECT 1 FROM faqs WHERE id = ?', [$id]) === null) {
            return false;
        }
        Database::update('faqs', ['question' => $question, 'answer' => $answer, 'category' => $category], ['id' => $id]);

        return true;
    }

    public static function delete(int $id): void
    {
        Database::execute('DELETE FROM faqs WHERE id = ?', [$id]);
    }
}
