<?php

// Filament assets published by Composer are bundled inside the PHP function.
// The runtime does not expose these generated files as Vercel static assets.
$requestPath = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
if (preg_match('~^/(css|js|fonts)/filament/~', $requestPath, $matches)) {
    $root = realpath(__DIR__.'/../public/'.$matches[1].'/filament');
    $file = realpath(__DIR__.'/../public'.$requestPath);
    $types = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
    ];
    $type = $file ? ($types[strtolower(pathinfo($file, PATHINFO_EXTENSION))] ?? null) : null;

    if (!$root || !$file || !str_starts_with($file, $root.DIRECTORY_SEPARATOR) || !is_file($file) || !$type) {
        http_response_code(404);
        exit;
    }
    if (!in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['GET', 'HEAD'], true)) {
        header('Allow: GET, HEAD', true, 405);
        exit;
    }

    header('Content-Type: '.$type);
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: public, max-age=86400');
    header('Content-Length: '.filesize($file));
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'HEAD') {
        readfile($file);
    }
    exit;
}

// Vercel only provides writable runtime storage in its temporary directory.
if (getenv('VERCEL')) {
    $storage = rtrim(sys_get_temp_dir(), '/\\').'/yakiniku-storage';

    foreach (['framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $directory) {
        $path = $storage.'/'.$directory;
        if (!is_dir($path) && !mkdir($path, 0700, true) && !is_dir($path)) {
            throw new RuntimeException('Unable to create runtime storage directory.');
        }
    }

    $_ENV['LARAVEL_STORAGE_PATH'] = $_SERVER['LARAVEL_STORAGE_PATH'] = $storage;
}

require __DIR__.'/../public/index.php';
