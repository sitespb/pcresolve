<?php

declare(strict_types=1);

namespace App\Support;

use Throwable;

final class ErrorHandler
{
    public static function handle(Throwable $e): void
    {
        self::log($e);

        if (PHP_SAPI === 'cli') {
            fwrite(STDERR, '[ERRO] ' . $e->getMessage() . PHP_EOL . $e->getFile() . ':' . $e->getLine() . PHP_EOL);
            exit(1);
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=UTF-8');
        }

        if (config('app.debug')) {
            echo '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><title>Erro</title></head>'
                . '<body style="font-family:ui-monospace,monospace;padding:24px;background:#fff5f5;color:#202124">'
                . '<h1 style="color:#D71920;font-size:18px">' . e(get_class($e)) . '</h1>'
                . '<p style="font-size:14px"><strong>' . e($e->getMessage()) . '</strong></p>'
                . '<p style="font-size:12px">' . e($e->getFile()) . ':' . $e->getLine() . '</p>'
                . '<pre style="font-size:11px;white-space:pre-wrap;background:#fff;padding:12px;border:1px solid #E4E7EC;border-radius:8px">' . e($e->getTraceAsString()) . '</pre>'
                . '</body></html>';
            exit;
        }

        echo render_fallback_error(500, 'Erro interno');
        exit;
    }

    public static function log(Throwable $e): void
    {
        $dir = BASE_PATH . '/storage/logs';
        $line = sprintf(
            "[%s] %s: %s em %s:%d\n%s\n\n",
            date('Y-m-d H:i:s'),
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        if (is_dir($dir) && is_writable($dir)) {
            @file_put_contents($dir . '/app-' . date('Y-m-d') . '.log', $line, FILE_APPEND | LOCK_EX);
        } else {
            error_log($line);
        }
    }
}
