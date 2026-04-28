<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

final class Application
{
    public Router $router;

    public function __construct(
        public array $config = [],
    ) {
        $this->router = new Router();
    }

    public function loadRoutes(string $path): void
    {
        $router = $this->router;
        require $path;
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        try {
            $this->router->dispatch($method, $uri);
        } catch (Throwable $e) {
            // B2 fix: surface a friendly 500 page in production while still
            // re-throwing in debug mode so devs see the trace.
            http_response_code(500);

            if (($this->config['debug'] ?? false) === true) {
                throw $e;
            }

            header('Content-Type: text/html; charset=UTF-8');
            echo $this->renderErrorPage();
        }
    }

    private function renderErrorPage(): string
    {
        $title = e((string) ($this->config['name'] ?? 'PixelVault')) . ' — Something went wrong';

        return <<<HTML
<!doctype html>
<html lang="en"><head><meta charset="UTF-8"><title>{$title}</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  body{margin:0;font-family:Inter,system-ui,sans-serif;background:#fbf9f4;color:#1a1612;
       min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;}
  .card{background:#fff;border:1px solid #e8e2d5;border-radius:1rem;padding:3rem;max-width:480px;text-align:center;
        box-shadow:0 12px 32px -16px rgba(24,16,12,.18);}
  .badge{display:inline-block;font-size:11px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;
         color:#e85d2f;background:rgba(232,93,47,.1);padding:.4rem .8rem;border-radius:.5rem;margin-bottom:1.5rem;}
  h1{font-family:"Instrument Serif",serif;font-size:2.2rem;margin:0 0 .5rem;font-weight:400;}
  p{color:#76685a;font-size:.95rem;line-height:1.6;margin:0 0 1.5rem;}
  a{display:inline-block;background:#1a1612;color:#fbf9f4;padding:.85rem 1.6rem;border-radius:.6rem;
    text-decoration:none;font-weight:600;font-size:.9rem;}
</style></head>
<body><div class="card">
  <div class="badge">Error 500</div>
  <h1>Something went wrong</h1>
  <p>Our team has been notified. Please try again in a moment.</p>
  <a href="/">Back to homepage</a>
</div></body></html>
HTML;
    }
}
