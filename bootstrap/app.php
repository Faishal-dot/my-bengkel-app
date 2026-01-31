<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // 1. Middleware global (TIDAK DIUBAH)
        $middleware->web(append: [
            \App\Http\Middleware\SaveLastVisitedUrl::class,
        ]);

        // 2. Alias middleware (TIDAK DIUBAH)
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'mechanic' => \App\Http\Middleware\IsMechanic::class, 
            'admin' => \App\Http\Middleware\IsAdmin::class,      
        ]);

        // ================================
        // ✅ FIX RESET PASSWORD (INI DOANG)
        // ================================
        $middleware->redirectUsersTo(function (Request $request) {

            // IZINKAN SEMUA ROUTE RESET PASSWORD
            if ($request->routeIs(
                'password.request',
                'password.reset',
                'password.store'
            )) {
                return null; // ❗ JANGAN REDIRECT
            }

            // DEFAULT JIKA BUKAN RESET
            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
