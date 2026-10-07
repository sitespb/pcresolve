<?php

declare(strict_types=1);

namespace App\Support;

final class Middleware
{
    public static function handle(string $name): void
    {
        match ($name) {
            'auth' => self::auth(),
            'guest' => self::guest(),
            'csrf' => self::csrf(),
            default => throw new \InvalidArgumentException("Middleware desconhecido: $name"),
        };
    }

    private static function auth(): void
    {
        if (Auth::check()) {
            Auth::touch();
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('X-Robots-Tag: noindex, nofollow');

            return;
        }

        if (request_method() === 'GET') {
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '/painel';
        }

        toast('Faça login para acessar o painel administrativo.', 'info');
        redirect('/painel/login');
    }

    private static function guest(): void
    {
        if (Auth::check()) {
            redirect('/painel');
        }
    }

    private static function csrf(): void
    {
        if (request_method() !== 'POST') {
            return;
        }

        $token = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
            toast('Sua sessão expirou. Recarregue a página e tente novamente.', 'error');
            redirect(previous_url('/'));
        }
    }
}
