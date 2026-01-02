<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PersonalAccessToken extends Model
{
    protected $table = "personal_access_tokens";
    protected $fillable = [
        'tokenable_id',
        'tokenable_type',
        'token',
        'expired_at'
    ];
    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }
}
