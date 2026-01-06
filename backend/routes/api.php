<?php

use App\Http\Controllers\cartController;
use App\Http\Controllers\productController;
use Illuminate\Support\Facades\Route;

Route::middleware('user.middle')->group(function () {});

Route::middleware('store.middle')->group(function () {});

Route::prefix('/cart')->group(function () {
    Route::get('/', [cartController::class, 'index']);
    Route::post('/create', [cartController::class, 'store']);
    Route::get('/{id}', [cartController::class, 'show']);
    Route::put('/{id}', [cartController::class, 'update']);
    Route::delete('/{id}', [cartController::class, 'destroy']);
});

Route::prefix('/product')->group(function () {
    Route::get('/', [productController::class, 'index']);
    Route::post('/create', [productController::class, 'store']);
    Route::get('/{id}', [productController::class, 'show']);
    Route::put('/{id}', [productController::class, 'update']);
    Route::delete('/{id}', [productController::class, 'destroy']);
});
