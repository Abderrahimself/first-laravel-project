<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\MustBeLoggedin;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Providers\InterventionImageServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    // Register the custom service provider
    ->withProviders([
        InterventionImageServiceProvider::class,
    ])
    ->create();
