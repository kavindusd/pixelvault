<?php

declare(strict_types=1);

namespace App\Core;

use Closure;

final class Router
{
    /**
     * @var array<string, array<int, array{pattern: string, handler: callable|array{0: class-string, 1: string}}>>
     */
    private array $routes = [];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function addRoute(string $method, string $pattern, callable|array $handler): void
    {
        $this->routes[strtoupper($method)][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $routes = $this->routes[strtoupper($method)] ?? [];

        foreach ($routes as $route) {
            $params = $this->match($route['pattern'], $path);

            if ($params === null) {
                continue;
            }

            $this->invoke($route['handler'], $params);
            return;
        }

        http_response_code(404);
        header('Content-Type: text/plain; charset=UTF-8');
        echo '404 Not Found';
    }

    /**
     * @return array<string, string>|null
     */
    private function match(string $pattern, string $path): ?array
    {
        $normalizedPattern = $pattern === '' ? '/' : $pattern;

        // Build regex: handle /{name?} (optional with leading slash) and {name} (required)
        $regex = preg_replace_callback(
            '#(/)?\\{([a-zA-Z_][a-zA-Z0-9_]*)(?::([^}]+))?(\\?)?\\}#',
            static function (array $m): string {
                $slash    = isset($m[1]) ? $m[1] : '';
                $name     = $m[2];
                $rule     = (isset($m[3]) && $m[3] !== '') ? $m[3] : '[^/]+';
                $optional = (isset($m[4]) && $m[4] === '?');

                $group = '(?P<' . $name . '>' . $rule . ')';
                if ($optional) {
                    return '(?:' . $slash . $group . ')?';
                }
                return $slash . $group;
            },
            $normalizedPattern
        );

        if (!preg_match('#^' . $regex . '$#', $path, $matches)) {
            return null;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    /**
     * @param callable|array{0: class-string, 1: string} $handler
     * @param array<string, string> $params
     */
    private function invoke(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $instance = new $class();
            $instance->{$method}($params);
            return;
        }

        if ($handler instanceof Closure || is_callable($handler)) {
            $handler($params);
            return;
        }
    }
}
