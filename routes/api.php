<?php

use App\Http\Controllers\PublicTransportController;
use App\Http\Controllers\UserController;
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

Route::post('sign_up', [UserController::class, 'signUp']);
Route::post('sign_in', [UserController::class, 'signIn']);

Route::group(['middleware' => ['jwt.authenticate']], function () {
});
Route::get('whoami', [UserController::class, 'whoami']);
Route::get('public_transport', [PublicTransportController::class, 'getAll']);
Route::get('public_transport/{public_transport}', [PublicTransportController::class, 'get']);
Route::post('public_transport', [PublicTransportController::class, 'add']);
Route::put('public_transport/{public_transport}', [PublicTransportController::class, 'update']);
Route::delete('public_transport/{public_transport}', [PublicTransportController::class, 'delete']);
