<?php

declare(strict_types=1);

const BASE_PATH = __DIR__ . '/..';

loadEnv(BASE_PATH . '/.env');
$db = require BASE_PATH . '/config/database.php';

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $db['host'],
    (int) $db['port'],
    $db['database'],
    $db['charset']
);

try {
    $pdo = new PDO($dsn, $db['username'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    fwrite(STDERR, "Database connection failed: {$e->getMessage()}" . PHP_EOL);
    exit(1);
}

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS schema_migrations (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL UNIQUE,
        executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;'
);

$executed = [];
$rows = $pdo->query('SELECT migration FROM schema_migrations')->fetchAll();
foreach ($rows as $row) {
    $executed[(string) $row['migration']] = true;
}

$migrationFiles = glob(__DIR__ . '/migrations/*.sql');
sort($migrationFiles);

foreach ($migrationFiles as $file) {
    $name = basename($file);

    if (isset($executed[$name])) {
        echo "[SKIP] {$name}" . PHP_EOL;
        continue;
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        fwrite(STDERR, "Could not read migration file: {$name}" . PHP_EOL);
        exit(1);
    }

    try {
        $pdo->exec($sql);
        $stmt = $pdo->prepare('INSERT INTO schema_migrations (migration) VALUES (:migration)');
        $stmt->execute(['migration' => $name]);
        echo "[OK]   {$name}" . PHP_EOL;
    } catch (Throwable $e) {
        fwrite(STDERR, "[FAIL] {$name} => {$e->getMessage()}" . PHP_EOL);
        exit(1);
    }
}

echo "Migrations completed." . PHP_EOL;

function loadEnv(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '#') || !str_contains($trimmed, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $trimmed, 2);
        $key = trim($key);
        $value = trim($value);

        if ($key === '') {
            continue;
        }

        $_ENV[$key] = $value;
        putenv(sprintf('%s=%s', $key, $value));
    }
}
