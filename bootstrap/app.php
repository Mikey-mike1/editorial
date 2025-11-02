<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * Aquí registramos los alias de middleware personalizados.
         * Esto reemplaza el antiguo Kernel.php en Laravel 11.
         */
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            // Puedes agregar más si los necesitas, por ejemplo:
            // 'auth' => \App\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
