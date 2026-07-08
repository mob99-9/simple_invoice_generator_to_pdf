<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Safeguard serverless write layers inside temporary runtime systems
if (isset($_SERVER['VERCEL_URL'])) {
    $writablePaths = ['/tmp/app', '/tmp/framework/cache', '/tmp/framework/views', '/tmp/framework/sessions', '/tmp/bootstrap/cache'];
    foreach ($writablePaths as $path) {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
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
        // Essential proxy validation for cross-environment serverless requests
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();