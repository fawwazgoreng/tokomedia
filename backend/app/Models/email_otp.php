<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class email_otp extends Model
{
    protected $table = 'email_otps';
    protected $primaryKey = 'id';
    protected $fillable = [
        'otp' , 'expires_at' , 'email' , 'for_id' , 'for_type'
    ];

    public function for() : MorphTo {
        return $this->morphTo();
    }
}
