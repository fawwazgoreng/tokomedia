<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class order_items extends Model
{
    protected $table = 'orderitems';
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
        'variants',
        'subtotal'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(order::class, 'order_id', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(product::class, 'product_id', 'id');
    }

    private static function recalculateOrder($orderItem)
    {
        if (! $orderItem->order) {
            return;
        }

        $order = $orderItem->order;

        $order->update([
            'total_products' => $order->order_items()->count(),
            'total_payment'  => $order->order_items()->sum('subtotal'),
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($orderItem) {
            self::recalculateOrder($orderItem);
        });

        static::updated(function ($orderItem) {
            self::recalculateOrder($orderItem);
        });

        static::deleted(function ($orderItem) {
            self::recalculateOrder($orderItem);
        });
    }
}
