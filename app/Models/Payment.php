<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'sale_id',
        'amount',
        'method',
        'status',
        'transaction_id'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function customer()
{
    return $this->belongsTo(Customer::class);
}
public function scopeEmi($query)
{
    return $query->where('method', 'emi');
}

}


