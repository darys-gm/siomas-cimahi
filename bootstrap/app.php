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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
        ]);

        // Tambahkan ini untuk mencegah redirect loop
        $middleware->redirectGuestsTo('/login');

        // ============================================
        // REGISTRASI TRACK VISITOR MIDDLEWARE
        // ============================================
        // Middleware ini akan otomatis mencatat kunjungan user
        // di semua halaman publik (kecuali admin, login, api, dll)
        $middleware->appendToGroup('web', \App\Http\Middleware\TrackVisitor::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();