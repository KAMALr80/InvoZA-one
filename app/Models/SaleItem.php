<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function sales()
{
    return $this->hasMany(Sale::class);
}

}
