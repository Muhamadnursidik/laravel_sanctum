<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    Route::resource('/posts', \App\Http\Controllers\Api\PostController::class)
        ->except(['create', 'edit']);

    Route::resource('/location', \App\Http\Controllers\Api\LocationController::class)
        ->except(['create', 'edit']);

    Route::resource('/field', \App\Http\Controllers\Api\FieldController::class)
        ->except(['create', 'edit']);

    Route::resource('/rating', \App\Http\Controllers\Api\RatingController::class)
        ->except(['create', 'edit']);

    Route::resource('/payment', \App\Http\Controllers\Api\PaymentController::class)
        ->except(['create', 'edit']);

    Route::resource('/booking', \App\Http\Controllers\Api\BookingController::class)
        ->except(['create', 'edit']);
});
