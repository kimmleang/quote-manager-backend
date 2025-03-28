<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;

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

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'error' => 'Unauthorized access. Please provide a valid token.'
        ], 401);
    }

    public function render($request, Throwable $exception)
    {
        // unauthenticated users
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'error' => 'Unauthorized access. Please provide a valid token.'
            ], 401);
        }
    
        // Handle all exceptions
        return parent::render($request, $exception);
    }
    
}