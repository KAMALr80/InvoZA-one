<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'invoice_no',
        'invoice_token',
        'sale_date',
        'sub_total',
        'discount',
        'tax',
        'grand_total',
        'payment_status',
    ];

    protected $casts = [
        'sale_date'   => 'datetime',
        'sub_total'   => 'float',
        'discount'    => 'float',
        'tax'         => 'float',
        'grand_total' => 'float',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /* Optional clean date */
    public function getFormattedDateAttribute()
    {
        return $this->sale_date
            ? $this->sale_date->format('d-m-Y')
            : null;
    }
    public function payments()
{
    return $this->hasMany(Payment::class);
}

public function latestPayment()
{
    return $this->hasOne(Payment::class)->latestOfMany();
}

}
