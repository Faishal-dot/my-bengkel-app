<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login dan punya role sesuai
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}