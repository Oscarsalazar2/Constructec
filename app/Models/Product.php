<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'image_url', 'stock'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function wholesalePrices()
    {
        return $this->hasMany(ProductWholesalePrice::class);
    }
}
