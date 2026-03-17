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
        'description',
        'quantity',
        'price',
        'category',
        'image'
    ];

    /* ==================== RELATIONSHIPS ==================== */

    /**
     * Get sale items for this product
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get purchases for this product
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id');
    }

    /* ==================== ACCESSORS ==================== */

    public function getImageUrlAttribute()
    {
        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return $this->image ? asset('storage/' . $this->image) : asset('images/no-image.png');
    }

    public function getFormattedPriceAttribute()
    {
        return '₹ ' . number_format($this->price, 2);
    }

    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return ['text' => 'Out of Stock', 'color' => 'red'];
        } elseif ($this->quantity <= 5) {
            return ['text' => 'Critical', 'color' => 'red'];
        } elseif ($this->quantity <= 10) {
            return ['text' => 'Low', 'color' => 'orange'];
        } elseif ($this->quantity <= 20) {
            return ['text' => 'Moderate', 'color' => 'yellow'];
        } else {
            return ['text' => 'Sufficient', 'color' => 'green'];
        }
    }
}
