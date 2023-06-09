<?php

use App\Http\Controllers\AuthDriverController;
use App\Http\Controllers\AuthPassengerController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::controller(AuthPassengerController::class)->group(function () {
    Route::post('v1/passenger/login', 'login');
    Route::post('v1/passenger/register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});



Route::controller(AuthDriverController::class)->group(function () {
    Route::post('v1/driver/login', 'login');
    Route::post('v1/driver/register', 'register');
    Route::post('v1/driver/refresh', 'refresh');
});
