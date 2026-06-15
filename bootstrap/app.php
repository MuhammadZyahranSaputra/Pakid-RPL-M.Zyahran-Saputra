<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request; // Jangan lupa tambahin ini

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // --- TAMBAHAN BARU: Redirect Pintar Multi-Role ---
        $middleware->redirectTo(
            guests: '/login', // Kalau belum login, usir ke /login
            users: function (Request $request) {
                // Kalau udah login, cek role-nya apa
                if (auth()->check()) {
                    if (auth()->user()->role === 'admin') {
                        return route('admin.dashboard');
                    }
                    if (auth()->user()->role === 'peserta') {
                        return route('peserta.galeri');
                    }
                }
                // Default pengunjung
                return route('dashboard');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();