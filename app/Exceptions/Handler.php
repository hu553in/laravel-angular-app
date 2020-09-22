<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
            return response()->common(404, null, ["Requested resource is not found"]);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->common(404, null, ["Requested HTTP resource is not found"]);
        }
        if ($exception instanceof QueryException) {
            $message = $exception->errorInfo[2];
            $errors = [];
            if (isset($message) && strlen($message) > 0) {
                array_push($errors, $message);
            }
            return response()->common(422, null, $errors);
        }
        $message = $exception->getMessage();
        $errors = [];
        array_push($errors, get_class($exception));
        if (isset($message) && strlen($message) > 0) {
            array_push($errors, $message);
        }
        return response()->common(500, null, $errors);
    }
}
