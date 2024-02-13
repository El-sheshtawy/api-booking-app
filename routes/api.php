<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\Owner\PropertyPhotoController;
use App\Http\Controllers\Public\ApartmentController;
use App\Http\Controllers\Public\PropertySearchController;
use App\Http\Controllers\User\BookingController;
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

Route::post('/auth/register', RegisterController::class)->middleware('guest');

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('owner')->group(function () {
        Route::get('/properties', [PropertyController::class, 'index']);
        Route::post('/properties', [PropertyController::class, 'store']);
        Route::post('/properties/{property}/photos', [PropertyPhotoController::class, 'store']);
        Route::post('/properties/{property}/photos/{photo}/reorder/{newPosition}', [PropertyPhotoController::class, 'reorder']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/', fn (Request $request) => $request->user());
        Route::apiResource('/bookings', BookingController::class);
    });

});

Route::get('/search', PropertySearchController::class);
Route::get('/properties/{property}', \App\Http\Controllers\Public\PropertyController::class);
Route::get('/apartments/{apartment}', ApartmentController::class);


