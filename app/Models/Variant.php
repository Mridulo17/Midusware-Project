<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'title', 'description'
    ];

    public function variantPrice(){
        return $this->hasOne('App\Models\ProductVariantPrice', 'product_variant_one', 'id');
    }
}
