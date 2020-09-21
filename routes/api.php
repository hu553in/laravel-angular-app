<?php

use App\Http\Controllers\PublicTransportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('public_transport', [PublicTransportController::class, 'getAll']);

Route::get('public_transport/{public_transport}', [PublicTransportController::class, 'get']);

Route::post('public_transport', [PublicTransportController::class, 'add']);

Route::put('public_transport/{public_transport}', [PublicTransportController::class, 'update']);

Route::delete('public_transport/{public_transport}', [PublicTransportController::class, 'delete']);
