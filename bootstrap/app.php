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
            'role' => \App\Http\Middleware\RequireRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Routing\Exceptions\InvalidSignatureException $e, $request) {
            $payload = ['valid' => false, 'message' => 'Invalid or expired QR code.'];

            if ($request->wantsJson()) {
                return response()->json($payload, 403);
            }

            return response()->view('checkin.result', $payload, 403);
        });
    })->create();
