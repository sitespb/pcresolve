<?php

declare(strict_types=1);

use App\Support\Database;
use App\Support\Env;
use App\Support\ErrorHandler;

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(static function (string $class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require BASE_PATH . '/app/helpers.php';

Env::load(BASE_PATH . '/.env');
$GLOBALS['__config'] = require BASE_PATH . '/app/config.php';

date_default_timezone_set((string) config('app.timezone', 'America/Fortaleza'));
mb_internal_encoding('UTF-8');

error_reporting(E_ALL);
ini_set('display_errors', config('app.debug') ? '1' : '0');
ini_set('log_errors', '1');

set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});
set_exception_handler([ErrorHandler::class, 'handle']);

Database::configure((array) config('db'));

function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $sessionDir = BASE_PATH . '/storage/sessions';
    if (is_dir($sessionDir) && is_writable($sessionDir)) {
        session_save_path($sessionDir);
        // Garante a limpeza de sessões antigas mesmo em PHP empacotado (gc_probability=0).
        ini_set('session.gc_probability', '1');
        ini_set('session.gc_divisor', '100');
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.gc_maxlifetime', (string) ((int) config('session.lifetime', 240) * 60));

    session_name((string) config('session.name', 'pcresolve_session'));
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}
