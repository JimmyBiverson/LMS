<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            if (!$user) {
                return route('dashboard');
            }
            return match ($user->role) {
                \App\Models\User::ROLE_ADMIN => route('admin.dashboard.dashboard'),
                \App\Models\User::ROLE_INSTRUCTOR => route('instructor.dashboard.dashboard'),
                \App\Models\User::ROLE_ORGANIZATION => route('org.dashboard.dashboard'),
                default => route('dashboard'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
