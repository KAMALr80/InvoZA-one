<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Customer extends Model
{

      use SoftDeletes;
    protected $fillable = [
    'name',
    'mobile',
    'email',
    'address',
    'gst_no',
    'open_balance',
];


    /**
     * Customer ke saare sales
     */
    public function sales()
    {
        return $this->hasMany(\App\Models\Sale::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function wallet()
    {
        return $this->hasMany(CustomerWallet::class)->orderBy('created_at', 'desc');
    }

    public function getCurrentWalletBalanceAttribute()
    {
        $latest = $this->wallet()->first();
        return $latest ? $latest->balance : 0;
    }

}
