<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class cart extends Model
{
    protected $fillable = [
        'user_id' , 'products_id' , 'jumlah' , 'variants_id'
    ];


    public function user (): BelongsTo {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

    public function product (): BelongsTo {
        return $this->belongsTo(product::class , 'products_id' , 'id');
    }

    public function variant (): BelongsTo {
        return $this->belongsTo(variants::class , 'variants_id' , 'id');
    }
}
