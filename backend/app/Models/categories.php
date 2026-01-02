<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class categories extends Model
{
    protected $fillable = [
        'name'
    ];

    public function products () : BelongsToMany {
        return $this->belongsToMany(product::class , 'categories_products');
    }
}
