<?php

namespace App\services;

use App\Models\refresh_token;
use App\Models\User;
use Illuminate\Support\Str;

class tokenReturn
{
    function createRefreshToken(User $user)
    {
        $random = Str::random(64);
        refresh_token::create([
            'name' => 'refresh_token',
            'tokenable_id' => $user->id,
            'tokenable_type' => User::class,
            'token' => hash('sha256', $random),
            'expires_at' => now()->addDays(7)
        ]);
        return $random;
    }
    function returnWithToken($user, $access_token, $refresh_token, $redirect = false)
    {
        $cookie = cookie('refresh_token', $refresh_token, 60 * 24 * 7, '/', null, false, true, false, 'Lax');
        if (!$redirect) {
            return response()->json([
                "token" => $access_token,
                "user" => $user
            ])->withCookie($cookie);
        }
        return redirect()->away(env("APP_FRONTEND_URL", "http://localhost:5173") . "/auth/success", 302, [
            "token" => $access_token,
            "user" => $user
        ])->withCookie($cookie);
    }
}
