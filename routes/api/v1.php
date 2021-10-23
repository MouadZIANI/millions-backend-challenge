<?php

use Illuminate\Support\Facades\Route;

/**
 * Auth Routes
 */
Route::prefix('auth')->as('auth:')->group(function () {
    Route::post(
        '/register',
        \App\Http\Controllers\Api\V1\Auth\RegisterController::class
    );

    Route::post(
        '/login',
        \App\Http\Controllers\Api\V1\Auth\LoginController::class
    );

    Route::post(
        '/logout',
        \App\Http\Controllers\Api\V1\Auth\LogoutController::class
    );
});

/**
 * Post Routes
 */
Route::prefix('posts')->as('posts:')->group(function () {
    Route::get(
        '/',
        \App\Http\Controllers\Api\V1\Posts\ToggleLikeController::class
    );

    Route::middleware('auth:api')->group(function () {
        Route::get(
            '/{post}/toggle-like',
            \App\Http\Controllers\Api\V1\Posts\ToggleLikeController::class
        );
    });
});
