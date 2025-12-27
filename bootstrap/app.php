<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'admin']) // Apply Auth & Admin Middleware
                ->prefix('admin')                       // URL: example.com/admin/dashboard
                ->name('admin.')                        // Route Name: admin.dashboard
                ->group(base_path('routes/admin.php')); // Load the file
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'check.blocked' => \App\Http\Middleware\CheckBlocked::class,
            'check_perm' => \App\Http\Middleware\CheckPermission::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\LanguageMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
