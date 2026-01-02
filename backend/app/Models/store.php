<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class store extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['name', 'password' , 'email' , 'foto_profil'];

    protected function casts()
    {
        return [
            'password' => 'hashed'
        ];
    }

    public function product () : HasMany {
        return $this->hasMany(product::class , 'store_id' , 'id');
    }
}
