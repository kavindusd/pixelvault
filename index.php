<?php

declare(strict_types=1);

// Handle static files for the PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $path = ltrim($url['path'], '/');
    if ($path !== '') {
        $rootFile = __DIR__ . DIRECTORY_SEPARATOR . $path;
        $publicFile = __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;
        
        if (file_exists($rootFile)) {
            return false;
        }
        if (file_exists($publicFile)) {
            $mimes = [
                'css'  => 'text/css',
                'js'   => 'application/javascript',
                'png'  => 'image/png',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif'  => 'image/gif',
                'svg'  => 'image/svg+xml',
                'webp' => 'image/webp',
            ];
            $ext = pathinfo($publicFile, PATHINFO_EXTENSION);
            if (isset($mimes[$ext])) {
                header('Content-Type: ' . $mimes[$ext]);
            }
            readfile($publicFile);
            return true;
        }
    }
}

require __DIR__ . '/bootstrap/app.php';

$app->run();
