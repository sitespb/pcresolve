<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Database;
use PDOException;

/** Solicitações de atendimento / Ordens de Serviço (LeadRequest na versão React). */
final class Lead
{
    /**
     * @param array<string, mixed> $data
     * @return string Protocolo gerado (ex.: OS-2026-0843)
     */
    public static function create(array $data): string
    {
        $now = now();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $protocol = self::nextProtocol();
            try {
                Database::insert('leads', [
                    'protocol' => $protocol,
                    'customer_name' => $data['customer_name'],
                    'phone' => $data['phone'],
                    'email' => ($data['email'] ?? '') !== '' ? $data['email'] : null,
                    'city' => $data['city'] ?? '',
                    'device_type' => $data['device_type'] ?? 'notebook',
                    'service_type' => $data['service_type'] ?? '',
                    'description' => $data['description'] ?? '',
                    'status' => 'pendente',
                    'source' => $data['source'] ?? 'site',
                    'ip_address' => $data['ip_address'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                return $protocol;
            } catch (PDOException $e) {
                // 23000 = protocolo duplicado em requisições simultâneas; tenta o próximo número.
                if ($e->getCode() !== '23000') {
                    throw $e;
                }
            }
        }

        throw new \RuntimeException('Não foi possível gerar o protocolo da solicitação.');
    }

    private static function nextProtocol(): string
    {
        $year = date('Y');
        $prefix = "OS-$year-";
        $last = (int) Database::value(
            'SELECT MAX(CAST(SUBSTRING(protocol, ?) AS UNSIGNED)) FROM leads WHERE protocol LIKE ?',
            [strlen($prefix) + 1, $prefix . '%']
        );

        return $prefix . str_pad((string) ($last + 1), 4, '0', STR_PAD_LEFT);
    }

    /** @return array<string, mixed>|null */
    public static function find(int $id): ?array
    {
        $row = Database::first('SELECT * FROM leads WHERE id = ?', [$id]);

        return $row ? self::hydrate($row) : null;
    }

    /**
     * Lista com busca (cliente, protocolo, telefone ou cidade) e filtro de status.
     *
     * @return array{items: list<array<string, mixed>>, total: int}
     */
    public static function search(string $query, string $status, int $page = 1, int $perPage = 25): array
    {
        $where = [];
        $params = [];

        if ($status !== '' && $status !== 'todos') {
            $where[] = 'status = ?';
            $params[] = $status;
        }

        if ($query !== '') {
            $like = '%' . $query . '%';
            $where[] = '(customer_name LIKE ? OR protocol LIKE ? OR phone LIKE ? OR city LIKE ?)';
            array_push($params, $like, $like, $like, $like);
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $total = (int) Database::value("SELECT COUNT(*) FROM leads $sqlWhere", $params);

        $offset = max(0, ($page - 1) * $perPage);
        $rows = Database::select(
            "SELECT * FROM leads $sqlWhere ORDER BY id DESC LIMIT $perPage OFFSET $offset",
            $params
        );

        return ['items' => array_map([self::class, 'hydrate'], $rows), 'total' => $total];
    }

    /** @return list<array<string, mixed>> */
    public static function recent(?string $status = null, int $limit = 5): array
    {
        $params = [];
        $where = '';
        if ($status !== null && $status !== 'todos') {
            $where = 'WHERE status = ?';
            $params[] = $status;
        }

        $rows = Database::select(
            "SELECT * FROM leads $where ORDER BY id DESC LIMIT " . max(1, $limit),
            $params
        );

        return array_map([self::class, 'hydrate'], $rows);
    }

    /** @return array<string, int> */
    public static function countsByStatus(): array
    {
        $counts = array_fill_keys(array_keys(lead_statuses()), 0);
        foreach (Database::select('SELECT status, COUNT(*) AS total FROM leads GROUP BY status') as $row) {
            $counts[(string) $row['status']] = (int) $row['total'];
        }

        return $counts;
    }

    public static function pendingCount(): int
    {
        return (int) Database::value("SELECT COUNT(*) FROM leads WHERE status = 'pendente'");
    }

    /** Soma de (orçamento final || estimado), como no dashboard original. */
    public static function totalBudget(): float
    {
        return (float) Database::value('SELECT COALESCE(SUM(COALESCE(final_budget, estimated_budget, 0)), 0) FROM leads');
    }

    public static function recentFromIp(string $ip, int $minutes): int
    {
        return (int) Database::value(
            'SELECT COUNT(*) FROM leads WHERE ip_address = ? AND created_at > (NOW() - INTERVAL ' . $minutes . ' MINUTE)',
            [$ip]
        );
    }

    /** Atualiza status, orçamento e anotações técnicas (modal "Gerenciar"). */
    public static function updateManagement(int $id, string $status, ?float $budget, string $notes): void
    {
        $budget = ($budget !== null && $budget > 0) ? $budget : null;

        Database::update('leads', [
            'status' => $status,
            'final_budget' => $budget,
            'estimated_budget' => $budget,
            'internal_notes' => $notes,
            'updated_at' => now(),
        ], ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::execute('DELETE FROM leads WHERE id = ?', [$id]);
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private static function hydrate(array $row): array
    {
        $row['id'] = (int) $row['id'];
        $row['estimated_budget'] = $row['estimated_budget'] !== null ? (float) $row['estimated_budget'] : null;
        $row['final_budget'] = $row['final_budget'] !== null ? (float) $row['final_budget'] : null;
        $row['budget'] = $row['final_budget'] ?: ($row['estimated_budget'] ?: null);
        $row['created_label'] = format_datetime((string) $row['created_at']);
        $row['updated_label'] = format_datetime((string) $row['updated_at']);

        return $row;
    }
}
