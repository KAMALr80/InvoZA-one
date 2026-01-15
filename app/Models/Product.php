<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'quantity',
        'price',
        'category'
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
