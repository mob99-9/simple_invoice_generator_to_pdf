<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Force writeable path on Vercel's immutable container filesystem
if (isset($_SERVER['VERCEL_URL'])) {
    $subFolders = ['/tmp/app', '/tmp/framework/cache', '/tmp/framework/views', '/tmp/framework/sessions', '/tmp/bootstrap/cache'];
    foreach ($subFolders as $folder) {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();