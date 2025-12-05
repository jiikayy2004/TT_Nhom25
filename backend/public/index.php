<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 */
define('LARAVEL_START', microtime(true));

// Register the auto loader classes, so the dependencies can be resolved.
// If you are "dead lifting" this file, you may as well add your own
// registers below. Nothing is stopping you from doing that...
if (!isset($argc)) {
    /**
     * Register custom auto loader.
     */
    require __DIR__.'/../vendor/autoload.php';
}

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
