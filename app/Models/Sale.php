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
        'tax_amount',
        'grand_total',
        'payment_status',
        'paid_amount',
        'requires_shipping',
        'shipping_address',
        'city',
        'state',
        'pincode',
    ];

    protected $casts = [
        'sale_date'   => 'datetime',
        'sub_total'   => 'float',
        'discount'    => 'float',
        'tax'         => 'float',
        'tax_amount'  => 'float',
        'grand_total' => 'float',
        'paid_amount' => 'float',
        'requires_shipping' => 'boolean',
    ];

    /* ==================== EXISTING RELATIONSHIPS ==================== */

    /**
     * Get the customer for this sale
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /**
     * Get the items for this sale
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the payments for this sale
     */
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    /**
     * Get the latest payment for this sale
     */
    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Get the EMI plan for this sale
     */
    public function emiPlan()
    {
        return $this->hasOne(EmiPlan::class);
    }

    /* ==================== NEW RELATIONSHIP ADDED ==================== */

    /**
     * Get the user who created this sale
     * Assumes sales table has created_by column
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ==================== SHIPMENT RELATIONSHIPS - NEW ==================== */

    /**
     * Get all shipments for this sale
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'sale_id');
    }

    /**
     * Get the latest shipment for this sale
     */
    public function latestShipment()
    {
        return $this->hasOne(Shipment::class, 'sale_id')->latestOfMany();
    }

    /**
     * Get pending shipments for this sale
     */
    public function pendingShipments()
    {
        return $this->hasMany(Shipment::class, 'sale_id')->where('status', 'pending');
    }

    /**
     * Get active shipments (not delivered) for this sale
     */
    public function activeShipments()
    {
        return $this->hasMany(Shipment::class, 'sale_id')
                    ->whereIn('status', ['pending', 'picked', 'in_transit', 'out_for_delivery']);
    }

    /**
     * Get delivered shipments for this sale
     */
    public function deliveredShipments()
    {
        return $this->hasMany(Shipment::class, 'sale_id')->where('status', 'delivered');
    }

    /* ==================== ACCESSORS & MUTATORS ==================== */

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->sale_date
            ? $this->sale_date->format('d-m-Y')
            : null;
    }

    /**
     * Get full shipping address
     */
    public function getFullShippingAddressAttribute()
    {
        if (!$this->shipping_address && !$this->city && !$this->state && !$this->pincode) {
            return $this->customer ? $this->customer->address : 'No address provided';
        }

        $address = $this->shipping_address ?? '';
        if ($this->city) $address .= ($address ? ', ' : '') . $this->city;
        if ($this->state) $address .= ($address ? ', ' : '') . $this->state;
        if ($this->pincode) $address .= ($address ? ' - ' : '') . $this->pincode;

        return $address ?: 'No address provided';
    }

    /**
     * Get tracking number for this sale (from latest shipment)
     */
    public function getTrackingNumberAttribute()
    {
        $shipment = $this->latestShipment;
        return $shipment ? $shipment->tracking_number : null;
    }

    /**
     * Get shipment status for this sale
     */
    public function getShipmentStatusAttribute()
    {
        $shipment = $this->latestShipment;
        return $shipment ? $shipment->status : 'no_shipment';
    }

    /**
     * Check if sale has active shipment
     */
    public function getHasActiveShipmentAttribute()
    {
        return $this->activeShipments()->exists();
    }

    /**
     * Get due amount for this sale
     */
    public function getDueAmountAttribute()
    {
        $totalPaid = $this->payments()
            ->where('status', 'paid')
            ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
            ->sum('amount');

        return max(0, $this->grand_total - $totalPaid);
    }

    /**
     * Get payment status with badge color
     */
    public function getPaymentStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'paid' => 'success',
            'partial' => 'warning',
            'emi' => 'info',
            default => 'danger'
        };
    }

    /* ==================== CUSTOM METHODS ==================== */

    /**
     * Generate shipment for this sale
     */
    public function createShipment($data = [])
    {
        $shipment = new Shipment();
        $shipment->sale_id = $this->id;
        $shipment->customer_id = $this->customer_id;
        $shipment->shipment_number = (new Shipment())->generateShipmentNumber();

        // Use sale data or provided data
        $customer = $this->customer;

        $shipment->receiver_name = $data['receiver_name'] ?? ($customer->name ?? 'Customer');
        $shipment->receiver_phone = $data['receiver_phone'] ?? ($customer->mobile ?? '');
        $shipment->receiver_alternate_phone = $data['receiver_alternate_phone'] ?? null;

        $shipment->shipping_address = $data['shipping_address'] ?? $this->full_shipping_address;
        $shipment->city = $data['city'] ?? $this->city ?? ($customer->city ?? '');
        $shipment->state = $data['state'] ?? $this->state ?? ($customer->state ?? '');
        $shipment->pincode = $data['pincode'] ?? $this->pincode ?? ($customer->pincode ?? '');
        $shipment->country = $data['country'] ?? 'India';

        $shipment->declared_value = $this->grand_total;
        $shipment->payment_mode = $this->payment_status === 'paid' ? 'prepaid' : 'cod';
        $shipment->shipping_method = $data['shipping_method'] ?? 'standard';

        $shipment->status = 'pending';
        $shipment->created_by = auth()->id() ?? 1; // Default to 1 if no auth
        $shipment->save();

        // Add initial tracking
        $shipment->updateTracking('pending', 'Shipment created from sale #' . $this->invoice_no);

        return $shipment;
    }

    /**
     * Recalculate payment status based on payments
     */
    public function recalculatePaymentStatus()
    {
        $totalPaid = $this->payments()
            ->where('status', 'paid')
            ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
            ->sum('amount');

        $this->paid_amount = $totalPaid;

        if ($totalPaid <= 0) {
            $this->payment_status = 'unpaid';
        } elseif ($totalPaid >= $this->grand_total) {
            $this->payment_status = 'paid';
        } else {
            $this->payment_status = 'partial';
        }

        $this->save();

        return $this->payment_status;
    }

    /**
     * Scope for unpaid invoices
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    /**
     * Scope for paid invoices
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope for partial invoices
     */
    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    /**
     * Scope for invoices requiring shipping
     */
    public function scopeRequiresShipping($query)
    {
        return $query->where('requires_shipping', true);
    }

    /**
     * Scope for invoices without shipment
     */
    public function scopeWithoutShipment($query)
    {
        return $query->whereDoesntHave('shipments');
    }
}
