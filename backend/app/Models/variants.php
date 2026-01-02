<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class variants extends Model
{
    protected $fillable = [
        'product_id' , 'sku' , 'stock' , 'price' , 'option_1' , 'option_2'
    ];

    public function product () : BelongsTo {
        return $this->belongsTo(product::class , 'product_id' , 'id');
    }
}
