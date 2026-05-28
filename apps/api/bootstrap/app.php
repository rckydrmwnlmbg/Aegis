<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\CorrelationIdMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {

                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
                $code = 'INTERNAL_ERROR';
                $details = [];

                if ($e instanceof ValidationException) {
                    $status = Response::HTTP_UNPROCESSABLE_ENTITY;
                    $code = 'VALIDATION_ERROR';
                    $details = $e->errors();
                } elseif ($e instanceof AuthenticationException) {
                    $status = Response::HTTP_UNAUTHORIZED;
                    $code = 'UNAUTHORIZED';
                } elseif ($e instanceof HttpException) {
                    $status = $e->getStatusCode();
                    $code = 'HTTP_ERROR_' . $status;
                }

                $message = $e->getMessage() ?: 'An unexpected error occurred';

                // For internal server errors in production, hide the message
                if ($status === Response::HTTP_INTERNAL_SERVER_ERROR && !config('app.debug')) {
                    $message = 'An internal server error occurred';
                }

                $response = [
                    'error' => [
                        'code' => $code,
                        'message' => $message,
                        'details' => empty($details) ? new \stdClass() : $details,
                    ]
                ];

                return response()->json($response, $status);
            }
        });
    })->create();
