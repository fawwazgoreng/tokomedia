<?php

use App\Models\refresh_token;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use function Illuminate\Support\now;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

Schedule::call(function () {
    refresh_token::where('expired_at' , '<' ,  now())->chunkById(100 , function ($tokens) {
        foreach ( $tokens as $token) {
            $token->delete();
        }
    });
})->daily();