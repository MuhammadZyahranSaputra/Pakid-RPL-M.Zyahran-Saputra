<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user udah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah role user saat ini ada di dalam daftar role yang diizinkan
        if (!in_array(Auth::user()->role, $roles)) {
            // Kalau nggak cocok, tendang ke halaman 403 Forbidden
            abort(403, 'Akses Ditolak: Lu nggak punya akses ke halaman ini.');
        }

        // Kalau cocok, persilakan masuk
        return $next($request);
    }
}