<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\RouteNotFoundException ;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use App\Http\Resources\ResponseFailureResource;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];


    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        // Implicite binding exceptions handled here
        if ($exception instanceof QueryException) {
            $response  = new ResponseFailureResource($exception, "Database error.");
            $http_code = Response::HTTP_INTERNAL_SERVER_ERROR;

            return response($response, $http_code);
        }

        // token validation error
        if ($exception instanceof RouteNotFoundException) {
            // dd("")
            $response = new ResponseFailureResource($exception, "Token validation error");

            $http_code = Response::HTTP_UNAUTHORIZED;

            return response($response, $http_code);
        }

        // token validation error
        if ($exception instanceof AuthenticationException) {
            $response = new ResponseFailureResource($exception, "Token validation error");

            $http_code = Response::HTTP_UNAUTHORIZED;

            return response($response, $http_code);
        }

        if ($exception instanceof ModelNotFoundException) {
            $response  = new ResponseFailureResource($exception, "Record not found.");
            $http_code = Response::HTTP_BAD_REQUEST;

            return response($response, $http_code);
        }

        if ($exception instanceof ValidationException) {
            return parent::render($request, $exception);
        }

        $response = new ResponseFailureResource($exception, "Internal error.");

        $http_code = (method_exists($exception, 'getStatusCode')) ? ($exception->getStatusCode()) : (Response::HTTP_INTERNAL_SERVER_ERROR);

        return response($response, $http_code);
    }
}
