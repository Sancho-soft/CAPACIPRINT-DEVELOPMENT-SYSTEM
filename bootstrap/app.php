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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/login',
            users: fn() => match(auth()->user()?->role) {
                'super_admin'        => '/admin/dashboard',
                'owner', 'management'=> '/management/dashboard',
                'admin'              => '/admin/dashboard',
                'manager'            => '/manager/dashboard',
                'production_officer' => '/manager/production-planning',
                'staff'              => '/staff/dashboard',
                'designer'           => '/designer/dashboard',
                'production'         => '/production/dashboard',
                'inventory'          => '/inventory/dashboard',
                default              => '/customer/dashboard',
            }
        );

        $middleware->alias([
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
            'role'     => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
