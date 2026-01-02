<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class refresh_token extends Model
{
    protected $fillable = [
        'tokenable_id' , 'tokenable_type' , 'token' , 'expires_at'
    ];
    public function tokenable () : MorphTo {
        return $this->morphTo();
    }

    public function user () {
        return $this->belongsTo(User::class , 'tokenable_id' , 'id');
    }
}
