<?php

use App\Http\Controllers\cartController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\productController;
use App\Models\order;
use Illuminate\Support\Facades\Route;

Route::get("/test" , function () {
    $res = order::query()->withCount(['order_items'])->find(2);
    return response()->json($res);
});
Route::middleware('user.middle')->group(function () {});

Route::middleware('store.middle')->group(function () {});

Route::prefix('/order')->group(function () {
    Route::get('/', [orderController::class, 'index']);
    Route::post('/create', [orderController::class, 'store']);
    Route::get('/{id}', [orderController::class, 'show']);
    Route::put('/update/{id}', [orderController::class, 'update']);
    Route::delete('/delete/{id}', [orderController::class, 'destroy']);
});

Route::prefix('/cart')->group(function () {
    Route::get('/', [cartController::class, 'index']);
    Route::post('/create', [cartController::class, 'store']);
    Route::get('/{id}', [cartController::class, 'show']);
    Route::put('/update/{id}', [cartController::class, 'update']);
    Route::delete('/delete/{id}', [cartController::class, 'destroy']);
});

Route::prefix('/product')->group(function () {
    Route::get('/', [productController::class, 'index']);
    Route::post('/create', [productController::class, 'store']);
    Route::get('/{id}', [productController::class, 'show']);
    Route::put('/update/{id}', [productController::class, 'update']);
    Route::delete('/delete/{id}', [productController::class, 'destroy']);
});
