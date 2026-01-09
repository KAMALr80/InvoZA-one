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
    public function sales()
{
    return $this->hasMany(Sale::class);
}

public function purchases()
{
    return $this->hasMany(Purchase::class);
}


}
