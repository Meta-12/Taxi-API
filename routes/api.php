<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMessageController;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// authentication route
Route::controller(UserController::class)->group(function () {
    Route::post('/auth/register', 'register');
    Route::post('/auth/login', 'login');
});
Route::get('/auth/google', [UserController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [UserController::class, 'handleGoogleCallback']);


// for admin route
Route::middleware('auth:api')->group(function () {

    Route::middleware('role:1')->group(function () {
        Route::controller(DirectionController::class)->group(function () {
            Route::post('/direction/addDirection', 'addDirection');
            Route::post('/direction/editDirection/{id}', 'editDirection');
            Route::get('/direction/getDirection', 'getDirection');
            Route::post('/direction/deleteDirection/{id}', 'deleteDirection');

            // for view booking in dashboard
            Route::controller(BookingController::class)->group(function () {
                Route::get('/booking/getBookingDashbord', 'getBookingDashbord');
            });

            // get user route
            Route::controller(UserController::class)->group(function () {
                Route::get('/user/getUser', 'getUser');
                Route::post('/user/changeRole/{id}', 'changeRole');
            });

            // for user message in dashboard
            Route::controller(UserMessageController::class)->group(function () {
                Route::get('/userMessage/viewMessage', 'viewMessage');
            });
        });
    });
});

// for booking
Route::controller(BookingController::class)->group(function () {
    Route::post('/booking/addBooking', 'addBooking');
    Route::get('/booking/getBooking', 'getBooking');
    Route::post('/booking/deleteBooking/{id}', 'deleteBooking');
});

// for view direction in front-end
Route::controller(DirectionController::class)->group(function () {
    Route::get('/direction/ViewDirection', 'ViewDirection');
});

// for user message
Route::controller(UserMessageController::class)->group(function () {
    Route::post('/userMessage/addMessage', 'addMessage');
});
