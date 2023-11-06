<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

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
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'status' => false,
                'message' => 'unauthenticated'
            ], 401);
        }


        if ($e instanceof RouteNotFoundException) {
            return response()->json([
                'status' => false,
                'message' => 'Not Found'
            ], 404);
        }


        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ]);

        return parent::render($request, $e);
    }
}
