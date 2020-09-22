<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register the application's response macros.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro(
            'common',
            function (int $statusCode, $data = null, array $errors = [], array $headers = []) {
                return Response::json([
                    'statusCode' => $statusCode,
                    'data' => $data,
                    'errors' => $errors,
                ], $statusCode, $headers);
            }
        );
    }
}
