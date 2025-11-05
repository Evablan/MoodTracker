<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    //Agregar los middlewares globales y alias
    ->withMiddleware(function (Middleware $middleware): void {
        // Middlewares que se ejecutan en TODAS las rutas web
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Alias para usar en rutas específicas
        $middleware->alias([
            'consented' => \App\Http\Middleware\EnsureUserConsented::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
