<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMechanic
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'mechanic') {
            return $next($request);
        }

        // Kalau bukan mekanik, arahkan ke halaman sesuai role-nya
        return redirect()->route('dashboard')->with('error', 'Akses ditolak: hanya untuk mekanik.');
    }
}