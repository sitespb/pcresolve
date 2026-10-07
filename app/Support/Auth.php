<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;

final class Auth
{
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 15;

    /** @var array<string, mixed>|null|false */
    private static array|null|false $user = false;

    /** @return array<string, mixed>|null */
    public static function user(): ?array
    {
        if (self::$user === false) {
            $id = $_SESSION['user_id'] ?? null;
            self::$user = $id ? User::find((int) $id) : null;

            if ($id && self::$user === null) {
                unset($_SESSION['user_id']);
            }
        }

        return self::$user;
    }

    public static function check(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $lifetime = (int) (config('session.lifetime') ?: 240) * 60;
        $last = (int) ($_SESSION['last_activity'] ?? 0);
        if ($last > 0 && time() - $last > $lifetime) {
            self::logout();

            return false;
        }

        return self::user() !== null;
    }

    public static function id(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    /** Atualiza o relógio de inatividade da sessão. */
    public static function touch(): void
    {
        $_SESSION['last_activity'] = time();
    }

    public static function tooManyAttempts(string $ip): bool
    {
        $count = (int) Database::value(
            'SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND created_at > (NOW() - INTERVAL ' . self::LOCKOUT_MINUTES . ' MINUTE)',
            [$ip]
        );

        return $count >= self::MAX_ATTEMPTS;
    }

    public static function attempt(string $email, string $password): bool
    {
        $ip = client_ip();
        $user = User::findByEmail($email);

        if ($user === null || !password_verify($password, (string) $user['password_hash'])) {
            Database::insert('login_attempts', [
                'ip_address' => $ip,
                'email' => mb_substr($email, 0, 190),
                'created_at' => now(),
            ]);

            return false;
        }

        if (password_needs_rehash((string) $user['password_hash'], PASSWORD_DEFAULT)) {
            User::setPassword((int) $user['id'], $password);
        }

        Database::execute('DELETE FROM login_attempts WHERE ip_address = ?', [$ip]);

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['last_activity'] = time();
        self::$user = false;

        User::recordLogin((int) $user['id'], $ip, (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), session_id());

        return true;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        self::$user = null;
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }
}
