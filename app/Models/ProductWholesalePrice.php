<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductWholesalePrice extends Model
{
    protected $fillable = [
        'product_id',
        'min_quantity',
        'max_quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
