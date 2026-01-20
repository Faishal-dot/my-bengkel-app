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
        // 1. Daftarkan Middleware secara Global agar mengecek setiap klik halaman
        $middleware->web(append: [
            \App\Http\Middleware\SaveLastVisitedUrl::class,
        ]);

        // 2. Alias middleware kustom (untuk digunakan di web.php)
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'mechanic' => \App\Http\Middleware\IsMechanic::class, 
            'admin' => \App\Http\Middleware\IsAdmin::class,      
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();