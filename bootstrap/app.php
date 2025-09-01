<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        RedirectIfAuthenticated::redirectUsing(fn() => route('Access | Gate'));
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'log_page_view' => \App\Http\Middleware\LogPageView::class,
            'verified.conditional' => \App\Http\Middleware\EnsureEmailIsConditionallyVerified::class,
        ])->appendToGroup('web', \App\Http\Middleware\DebugbarMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->withProviders([
        App\Providers\ViewServiceProvider::class,
    ])->create();
