<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class order extends Model
{
    protected $fillable = [
        'user_id' ,'order_date' , 'payment_status' , 'status' , 'total_payment' , 'total_products'
    ];

    public function user () {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }

    public function order_items () : HasMany {
        return $this->hasMany(order_items::class , 'order_id' , 'order_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_id = "TM-" . Str::random(10) . $order->id;
        });
    }
}
