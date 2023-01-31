<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
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

Route::post('/send-code', [AuthController::class, 'sendCode']);
Route::post('/confirm-code/{user}', [AuthController::class, 'confirmCode']);

Route::middleware('auth')->group(function () {
    Route::post('/edit-profile', [UserController::class, 'editProfile']);
    Route::post('/send-reservation-request/{photographer}', [ReservationController::class, 'sendReservationRequest']);
    Route::get('/accept-reservation/{reservation}', [ReservationController::class, 'acceptReservation']);
    Route::post('/attach-file/{reservation}', [ReservationController::class, 'attachFilesToReservation']);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
