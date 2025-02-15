<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);

        Route::any('logout', [AuthController::class, 'logout']);
    });
});

// Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('shop')->group(function () {
        Route::get('banners', [ShopController::class, 'banners']);
        Route::get('collections', [ShopController::class, 'collections']);
        Route::get('products', [ShopController::class, 'products']);
    });
// });
