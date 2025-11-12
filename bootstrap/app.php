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
        // Alias middleware kustom
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'mechanic' => \App\Http\Middleware\IsMechanic::class, // ✅ Tambahkan ini
            'admin' => \App\Http\Middleware\IsAdmin::class,       // ✅ Tambahkan juga jika belum
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();