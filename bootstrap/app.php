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
            'instructor_approved' => \App\Http\Middleware\CheckInstructorApproval::class,
        ]);

        $middleware->redirectTo(
            users: function () {
                $user = auth()->user();
                if (!$user) {
                    return route('login');
                }
                return match ($user->role) {
                    \App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_STAFF => route('admin.dashboard.dashboard'),
                    \App\Models\User::ROLE_INSTRUCTOR => route('instructor.dashboard.dashboard'),
                    \App\Models\User::ROLE_ORGANIZATION => route('org.dashboard.dashboard'),
                    default => route('dashboard'),
                };
            },
            guests: fn() => route('login'),
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
