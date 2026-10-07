<?php

declare(strict_types=1);

use App\Support\Env;

return [
    'app' => [
        'env' => Env::get('APP_ENV', 'production'),
        'debug' => Env::bool('APP_DEBUG', false),
        'url' => rtrim((string) Env::get('APP_URL', ''), '/'),
        'timezone' => Env::get('APP_TIMEZONE', 'America/Fortaleza'),
        // Botão "Restaurar Padrão de Demonstração" (apaga e recria os dados do site).
        'allow_demo_reset' => Env::bool('ALLOW_DEMO_RESET', false),
    ],

    'db' => [
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => (int) Env::get('DB_PORT', '3306'),
        'database' => Env::get('DB_DATABASE', 'pcresolve'),
        'username' => Env::get('DB_USERNAME', 'root'),
        'password' => Env::get('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],

    'session' => [
        'name' => Env::get('SESSION_NAME', 'pcresolve_session'),
        // Minutos de inatividade até a sessão do painel expirar.
        'lifetime' => (int) Env::get('SESSION_LIFETIME', '240'),
    ],
];
