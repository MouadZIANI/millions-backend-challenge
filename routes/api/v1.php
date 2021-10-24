<?php

use Illuminate\Support\Facades\Route;

/**
 * Auth Routes
 */
Route::prefix('auth')->as('auth:')->group(function () {
    Route::post(
        '/register',
        \App\Http\Controllers\Api\V1\Auth\RegisterController::class
    )->name('register');

    Route::post(
        '/login',
        \App\Http\Controllers\Api\V1\Auth\LoginController::class
    )->name('login');

    Route::post(
        '/logout',
        \App\Http\Controllers\Api\V1\Auth\LogoutController::class
    )->name('logout');
});

/**
 * Post Routes
 */
Route::prefix('posts')->as('posts:')->group(function () {
    Route::get(
        '/',
        \App\Http\Controllers\Api\V1\Posts\IndexController::class
    )->name('index');

    Route::middleware('auth:api')->group(function () {
        Route::post(
            '/',
            \App\Http\Controllers\Api\V1\Posts\StoreController::class
        )->name('store');

        Route::get(
            '/{uuid}/likes',
            \App\Http\Controllers\Api\V1\Posts\LikesController::class
        )->name('likes');

        Route::get(
            '/{uuid}/toggle-like',
            \App\Http\Controllers\Api\V1\Posts\ToggleLikeController::class
        )->name('toggle-like');

        Route::delete(
            '/{uuid}',
            \App\Http\Controllers\Api\V1\Posts\DestroyController::class
        )->name('delete');
    });
});
