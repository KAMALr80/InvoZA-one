<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerWallet extends Model
{
    use HasFactory;

  protected $fillable = [
    'customer_id',
    'type',        // credit | debit
    'amount',
    'balance',
    'reference'
];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
