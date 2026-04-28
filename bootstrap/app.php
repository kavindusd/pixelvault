<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

if (is_file(BASE_PATH . '/.env')) {
    $lines = file(BASE_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '#') || !str_contains($trimmed, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $trimmed, 2);
        $key = trim($key);
        $value = trim($value);
        // B18 fix: strip surrounding single or double quotes so values like
        // JWT_SECRET="abc=def" don't include the quote characters in the
        // resulting env value.
        if (strlen($value) >= 2) {
            $first = $value[0];
            $last = $value[strlen($value) - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }
        $_ENV[$key] = $value;
        putenv($key . '=' . $value);
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = BASE_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($path)) {
        require $path;
    }
});

require BASE_PATH . '/app/helpers.php';

$config = require BASE_PATH . '/config/app.php';
$app = new App\Core\Application($config);

$app->loadRoutes(BASE_PATH . '/routes/api.php');
$app->loadRoutes(BASE_PATH . '/routes/web.php');
