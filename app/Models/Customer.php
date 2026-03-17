<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'address',
        'gst_no',
        'open_balance',
    ];

    /* ==================== RELATIONSHIPS ==================== */

    /**
     * Get all sales for this customer
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get all payments for this customer
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get wallet transactions for this customer
     */
    public function wallet()
    {
        return $this->hasMany(CustomerWallet::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get current wallet balance
     */
    public function getCurrentWalletBalanceAttribute()
    {
        $latest = $this->wallet()->first();
        return $latest ? $latest->balance : 0;
    }

    /**
     * Get full name with mobile for display
     */
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->mobile . ')';
    }
}
