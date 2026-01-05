<?php

use App\Http\Controllers\productController;
use Illuminate\Support\Facades\Route;

Route::middleware("user.middle")->group(function () {});

Route::prefix("/product")->group(function () {
    Route::get('/', [productController::class, 'index']);
    Route::post('/create', [productController::class, 'store']);
    Route::get('/{id}', [productController::class , 'show']);
    Route::put('/{id}', [productController::class , 'update']);
    Route::delete('/{id}', [productController::class , 'destroy']);
});
