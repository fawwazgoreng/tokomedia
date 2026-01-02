<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class product extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'products';
    protected $fillable = [
        'name','gambar', 'store_id'
    ];

    public function variants () : HasMany {
        return $this->hasMany(variants::class , 'product_id' , 'id');
    }

    public function variant () : HasOne {
        return $this->hasOne(variants::class , 'product_id' , 'id');
    }

    public function store () : BelongsTo {
        return $this->belongsTo(store::class , 'store_id' , 'id');
    }

    public function categories () : BelongsToMany {
        return $this->belongsToMany(categories::class , 'categories_products');
    }

        public function cart (): BelongsToMany {
        return $this->belongsToMany(cart::class , 'carts_products');
    }

}
