<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SaveLastVisitedUrl
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // PERBAIKAN: Menggunakan Auth::check() untuk memeriksa login
        if (Auth::check() && $request->isMethod('get') && !$request->routeIs('home', 'logout')) {
            
            // Kita juga pastikan tidak menyimpan URL rute debug atau internal lainnya
            if (!$request->ajax() && !$request->prefetch()) {
                Session::put('last_visited_url', $request->fullUrl());
            }
        }

        return $next($request);
    }
}