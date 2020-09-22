<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->common(Response::HTTP_NOT_FOUND, null, ["Requested resource is not found"]);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->common(Response::HTTP_NOT_FOUND, null, ["Requested HTTP resource is not found"]);
        }
        if ($exception instanceof QueryException) {
            $message = $exception->errorInfo[2];
            $errors = [];
            if (isset($message) && strlen($message) > 0) {
                array_push($errors, $message);
            }
            return response()->common(Response::HTTP_UNPROCESSABLE_ENTITY, null, $errors);
        }
        if ($exception instanceof ValidationException) {
            return response()->common(Response::HTTP_BAD_REQUEST, null, $exception->validator->errors()->all());
        }
        $message = $exception->getMessage();
        $errors = [];
        if (isset($message) && strlen($message) > 0) {
            array_push($errors, $message);
        }
        return response()->common(Response::HTTP_INTERNAL_SERVER_ERROR, null, $errors);
    }
}
