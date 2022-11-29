<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (TokenInvalidException $e, $request) {
            return Response::json(['error' => 'Invalid token'], 401);
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            return Response::json(['error' => 'Token has Expired'], 401);
        });

        $this->renderable(function (JWTException $e, $request) {
            return Response::json(['error' => 'Token not parsed'], 401);
        });

        $this->renderable(function (UnauthorizedHttpException $e, $request) {
            return Response::json(['error' => 'Not authorized'], 403);
        });

        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() == 404) {
                return Response::json(['error' => 'Not found'], 404);
            }

            // error 403
            if ($e->getStatusCode() == 403) {
                return Response::json(['error' => 'Not authorized'], 403);
            }

            if ($e->getStatusCode() == 500) {
                return Response::json(['error' => 'Internal server error'], 500);
            }

            return $this->prepareJsonResponse($request, $e);
        });
    }
}
