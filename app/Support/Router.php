<?php

declare(strict_types=1);

namespace App\Support;

final class Router
{
    /** @var list<array{method: string, regex: string, handler: callable|array{0: class-string, 1: string}, middleware: list<string>}> */
    private array $routes = [];

    /** @var list<string> */
    private array $groupMiddleware = [];

    /** @param callable|array{0: class-string, 1: string} $handler */
    public function get(string $pattern, callable|array $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    /** @param callable|array{0: class-string, 1: string} $handler */
    public function post(string $pattern, callable|array $handler, array $middleware = []): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    /**
     * Agrupa rotas com o mesmo conjunto de middlewares.
     *
     * @param list<string> $middleware
     */
    public function group(array $middleware, callable $routes): void
    {
        $previous = $this->groupMiddleware;
        $this->groupMiddleware = array_merge($previous, $middleware);
        $routes($this);
        $this->groupMiddleware = $previous;
    }

    /** @param callable|array{0: class-string, 1: string} $handler */
    private function add(string $method, string $pattern, callable|array $handler, array $middleware): void
    {
        // /servicos/{slug} -> ([^/]+) ; /painel/x/{id:\d+} -> (\d+)
        $regex = preg_replace_callback(
            '#\{([a-zA-Z_]+)(?::([^}]+))?\}#',
            fn (array $m) => '(?P<' . $m[1] . '>' . ($m[2] ?? '[^/]+') . ')',
            rtrim($pattern, '/') ?: '/'
        );

        $this->routes[] = [
            'method' => $method,
            'regex' => '#^' . $regex . '$#u',
            'handler' => $handler,
            'middleware' => array_merge($this->groupMiddleware, $middleware),
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $path = '/' . trim($path, '/');
        $method = strtoupper($method);
        if ($method === 'HEAD') {
            $method = 'GET';
        }

        $allowed = [];

        foreach ($this->routes as $route) {
            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }

            if ($route['method'] !== $method) {
                $allowed[] = $route['method'];
                continue;
            }

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $params = array_map('rawurldecode', $params);

            foreach ($route['middleware'] as $name) {
                Middleware::handle($name);
            }

            $handler = $route['handler'];
            if (is_array($handler)) {
                [$class, $action] = $handler;
                $handler = [new $class(), $action];
            }

            $handler(...$params);

            return;
        }

        if ($allowed !== []) {
            http_response_code(405);
            header('Allow: ' . implode(', ', array_unique($allowed)));
            abort(405);
        }

        abort(404);
    }
}
