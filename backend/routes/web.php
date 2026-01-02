<?php

use App\Http\Controllers\OauthUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test', function () {
//     $res = '';
//     for ($i = 0 ; $i < 7 ; $i++) {
//         $res = $res . str(random_int(1 ,10));
//     }
//     return response()->json($res);
// });

Route::get('/auth/google/redirect', [OauthUserController::class, 'redirect']);
Route::get('/auth/google/callback', [OauthUserController::class, 'callback']);
// Route::prefix('user')->group(function () {
// Route::post('/login' , [OauthUserController::class , '']);
// });
Route::prefix('api/user')->group(function () {
    Route::post('/login', [OauthUserController::class, 'login']);
    Route::post('/register', [OauthUserController::class, 'register']);
    Route::post('/refresh', [OauthUserController::class, 'refresh']);
    Route::middleware("user.middle")->group(function () {
        Route::delete('/logout', [OauthUserController::class, 'logout']);
        Route::get("/profile", [OauthUserController::class, 'profile']);
    });
});
