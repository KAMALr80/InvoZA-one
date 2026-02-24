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
     public function sourcePayments()
    {
        return $this->hasMany(Payment::class, 'source_wallet_id');
    }
      public function payments()
    {
        return $this->hasMany(Payment::class, 'wallet_id');
    }
}
