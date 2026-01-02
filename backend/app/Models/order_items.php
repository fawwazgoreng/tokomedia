<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class order_items extends Model
{
    protected $table = 'orderitems'; 
    protected $fillable = [
        'order_id' , 'product_id' , 'price' , 'quantity' , 'variants' , 'subtotal'
    ];

    public function order () : BelongsTo {
        return $this->belongsTo(order::class , 'order_id' , 'order_id');
    }

    public function product () {
        return $this->belongsTo(product::class , 'product_id' , 'id');
    }
}
