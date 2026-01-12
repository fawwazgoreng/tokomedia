<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class order extends Model
{
    protected $fillable = [
        'user_id',
        'order_date',
        'payment_status',
        'status',
        'total_payment',
        'total_products'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function order_items(): HasMany
    {
        return $this->hasMany(order_items::class, 'order_id', 'order_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_id = "TM-" . Str::random(30) . $order->id;
        });
    //     static::created(function ($order) {
    //         if ($order->order_items) {
    //             $order->total_products++;
    //             $order->total_payment += $order->order_items->subtotal;
    //         }
    //     });
    //     static::deleted(function ($order) {
    //         if ($order->order_items && $order->order_items->total_products > 0) {
    //             $order->order_items->total_products--;
    //             if ($order->order_items->total_payment > 0) {
    //                 $order->order_items->total_payment -= $order->order_items->subtotal;
    //             }
    //         }
    //     });
    //     static::updated(function ($order) {
    //         if ($order->isDirty('id')) {
    //             $oldOrderItems = order::find($order->getOriginal('id'));
    //             if ($oldOrderItems && $order->total_products > 0) {
    //                 $order->order_items->total_products--;
    //                 if ($order->order_items->total_payment > 0) {
    //                     $order->order_items->total_payment -= $order->order_items->subtotal;
    //                 }
    //             }
    //             if ($order->order_items) {
    //                 $order->total_products++;
    //                 $order->total_payment += $order->order_items->subtotal;
    //             }
    //         }
    //     });
    }
}
