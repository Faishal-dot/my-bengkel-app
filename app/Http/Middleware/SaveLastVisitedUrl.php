<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SaveLastVisitedUrl
{
    public function handle(Request $request, Closure $next)
    {
        // 🚫 MATIKAN TOTAL middleware untuk halaman auth & reset
        if ($request->routeIs(
            'login',
            'register',
            'password.request',
            'password.reset',
            'password.store'
        )) {
            return $next($request);
        }

        // ✅ SIMPAN URL HANYA UNTUK USER SUDAH LOGIN & HALAMAN NORMAL
        if (
            Auth::check() &&
            $request->isMethod('get') &&
            !$request->routeIs('home', 'logout') &&
            !$request->ajax()
        ) {
            Session::put('last_visited_url', $request->fullUrl());
        }

        return $next($request);
    }
}
