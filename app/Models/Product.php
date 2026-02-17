<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'description',
        'quantity',
        'price',
        'category',
        'image'
    ];

    // Accessor for image URL
     public function getImageUrlAttribute()
    {
        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Agar local storage se image hai
        return $this->image ? asset('storage/' . $this->image) : asset('images/no-image.png');
    }
     public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
