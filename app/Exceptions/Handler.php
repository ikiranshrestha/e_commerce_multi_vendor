<?php

namespace App\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $api = new ApiResponse;

        // Model not found
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return $api->error('Resource not found', 404);
        }

        // Validation errors
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return $api->error('Validation failed', 422, $e->errors());
        }

        // Authentication
        if ($e instanceof \Illuminate\Auth\AuthenticationException) {
            return $api->error('Unauthenticated', 401);
        }

        // Authorization
        if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return $api->error('Forbidden', 403);
        }

        if ($request->expectsJson()) {
            return \App\Support\ApiResponse::exception(
                $e->getMessage(),
                config('app.debug') ? $e->getTrace() : null,
                method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500
            );
        }

        // Fallback
        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        return $api->error('Server error', 500);
    }
}
