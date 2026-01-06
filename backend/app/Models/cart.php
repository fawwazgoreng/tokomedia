<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class cart extends Model
{
    protected $fillable = [
        'user_id' , 'products_id' , 'jumlah' , 'variants_id'
    ];


    public function user (): BelongsTo {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

    public function product (): BelongsToMany {
        return $this->belongsToMany(product::class , 'carts_products');
    }
}
