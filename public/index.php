<?php

declare(strict_types=1);

use App\Support\Router;

require dirname(__DIR__) . '/app/bootstrap.php';

start_session();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

$router = new Router();
require BASE_PATH . '/app/routes.php';

$router->dispatch((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'), request_path());
