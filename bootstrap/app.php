<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group("api", [
            \App\Http\Middleware\SecureContactApi::class,
            \App\Http\Middleware\ThrottleContacts::class,
        ]);
        $middleware->alias([
            'api.secure' => \App\Http\Middleware\SecureContactApi::class,
            'throttle.contacts' => \App\Http\Middleware\ThrottleContacts::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
