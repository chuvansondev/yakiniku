<?php

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
