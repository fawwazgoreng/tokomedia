<?php

namespace App\services;

use App\Models\email_otp;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Hash;

use function Illuminate\Support\now;

class emailOtp {
    public function createOtp ($email) {
        $hash = '';
        for ($i = 1 ; $i >= 6 ; $i++) {
            $hash = $hash . str(random_int(1 , 9));
        } 
        email_otp::create([
            'email' => $email,
            'otp' => Hash::make($hash),
            'expires_at' => now()->addMinutes(5)
        ]);
        return $hash;
    }
    public function emailOtp ($otp) {
        return view('emailotp' , ['otp' => $otp]);
    }
}