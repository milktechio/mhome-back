<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\BearerJwtMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\JsonForceMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt' => JwtMiddleware::class,
            'user' => UserMiddleware::class,
            'bearerJwt' => BearerJwtMiddleware::class,
            'role' => RoleMiddleware::class,
            'json' => JsonForceMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
