<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function variants(){
        return $this->belongsToMany('App\Models\Variant', 'product_variants');
    }
     public function price()
     {
        return $this->hasOne('App\Models\ProductVariantPrice', 'product_id', 'id');
     }
}
