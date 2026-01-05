<?php

namespace App\services;

use App\Models\email_otp;

use function Illuminate\Support\now;

class emailOtp {
    public function createOtp ($email , $user) {
        $hash = rand(100000 , 999999);
        $email_otp = email_otp::where('email' , $email);
        if ($email_otp) {
            $email_otp->delete();
        }
        email_otp::create([
            'email' => $email,
            'otp' => hash('sha256', $hash),
            'for_id' => $user->id,
            'for_type' => $user::class,
            'expires_at' => now()->addMinutes(5)
        ]);
        return $hash;
    }
}
