<?php

use App\Http\Controllers\productController;
use App\Http\Controllers\userLogController;
use Illuminate\Support\Facades\Route;


Route::get('/auth/google/redirect', [userLogController::class, 'redirect']);
Route::get('/auth/google/callback', [userLogController::class, 'callback']);


Route::prefix('api/')->group(function () {
    Route::post('otp/verification' , [userLogController::class , 'verifyedOtp']);
    Route::prefix('user')->group(function () {
        Route::post('/login', [userLogController::class, 'login']);
        Route::post('/register', [userLogController::class, 'register']);
        Route::post('/refresh', [userLogController::class, 'refresh']);
        Route::middleware("user.middle")->group(function () {
            Route::delete('/logout', [userLogController::class, 'logout']);
            Route::get("/profile", [userLogController::class, 'profile']);
        });
    });
});
