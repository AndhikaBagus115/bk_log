<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException; // <-- Tambahkan ini
use Illuminate\Http\Request;                 // <-- Tambahkan ini
use Illuminate\Support\Arr;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan alias middleware Anda di sini
        $middleware->alias([
            'auth.client' => \Illuminate\Auth\Middleware\Authenticate::class . ':client',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Tambahkan blok ini untuk menangani error autentikasi
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            // Jika permintaan adalah API, kembalikan JSON
            if ($request->is('api/*')) {
                return response()->json(['message' => $e->getMessage()], 401);
            }

            // Ambil guard yang menyebabkan error
            $guard = Arr::get($e->guards(), 0);

            // Jika guard-nya adalah 'client', arahkan ke login client
            if ($guard === 'client') {
                return redirect()->guest(route('login')); // Pastikan 'login' adalah nama route login Anda
            }

            // Redirect default untuk guard lainnya
            return redirect()->guest(route('login'));
        });
    })->create();
