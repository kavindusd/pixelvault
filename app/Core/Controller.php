<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = [], string $layout = 'layouts/app'): void
    {
        extract($data, EXTR_SKIP);
        $viewPath = BASE_PATH . '/app/Views/' . $view . '.php';
        $layoutPath = BASE_PATH . '/app/Views/' . $layout . '.php';

        require $layoutPath;
    }

    protected function partial(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require BASE_PATH . '/app/Views/' . $view . '.php';
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
