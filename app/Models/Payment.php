<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'sale_id',
        'customer_id',
        'amount',
        'method',
        'status',
        'transaction_id',
        'remarks',
        'payment_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the sale associated with this payment
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the customer associated with this payment
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Scope for EMI payments
     */
    public function scopeEmi($query)
    {
        return $query->where('method', 'emi');
    }

    /**
     * Scope for invoice payments (not advance, not excess)
     */
    public function scopeInvoicePayments($query)
    {
        return $query->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'EMI_MONTHLY']);
    }

    /**
     * Scope for advance payments
     */
    public function scopeAdvancePayments($query)
    {
        return $query->whereIn('remarks', ['ADVANCE_ONLY', 'EXCESS']);
    }

    /**
     * Scope for paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for cancelled payments
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Check if payment is for invoice
     */
    public function isInvoicePayment()
    {
        return in_array($this->remarks, ['INVOICE', 'EMI_DOWN', 'EMI_MONTHLY']);
    }

    /**
     * Check if payment is advance
     */
    public function isAdvancePayment()
    {
        return in_array($this->remarks, ['ADVANCE_ONLY', 'EXCESS']);
    }

    /**
     * Check if payment used advance
     */
    public function isAdvanceUsed()
    {
        return in_array($this->remarks, ['ADVANCE_USED', 'ADVANCE_USED_EMI']);
    }

    /**
     * Get payment method icon
     */
    public function getMethodIconAttribute()
    {
        return match ($this->method) {
            'cash' => '💵',
            'upi' => '📱',
            'card' => '💳',
            'net_banking' => '🏦',
            'emi' => '📆',
            'advance' => '👛',
            default => '💰'
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'paid' => 'green',
            'cancelled' => 'red',
            'pending' => 'yellow',
            default => 'gray'
        };
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₹ ' . number_format($this->amount, 2);
    }

    /**
     * Get payment date formatted
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y - h:i A');
    }

    /**
     * Get short date
     */
    public function getShortDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }
}
