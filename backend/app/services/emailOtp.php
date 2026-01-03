<?php

namespace App\services;

use App\Models\email_otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Illuminate\Support\now;

class emailOtp {
    public function createOtp ($email , $user) {
        $hash = '';
        $email_otp = email_otp::where('email' , $email)->where('for_id' , $user->id);
        if ($email_otp) {
            $email_otp->delete();
        }
        for ($i = 1 ; $i <= 6 ; $i++) {
            $hash = $hash . str(random_int(1 , 9));
        }
        email_otp::create([
            'email' => $email,
            'otp' => $hash,
            'for_id' => $user->id,
            'for_type' => User::class,
            'expires_at' => now()->addMinutes(5)
        ]);
        return $hash;
    }
}