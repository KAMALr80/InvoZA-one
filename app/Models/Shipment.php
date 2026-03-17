<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'shipment_number', 'sale_id', 'customer_id', 'assigned_to',
        'receiver_name', 'receiver_phone', 'receiver_alternate_phone',
        'shipping_address', 'landmark', 'city', 'state', 'pincode', 'country',
        'weight', 'length', 'width', 'height', 'quantity', 'declared_value', 'package_type',
        'shipping_method', 'courier_partner', 'tracking_number', 'awb_number',
        'shipping_charge', 'cod_charge', 'insurance_charge', 'total_charge', 'payment_mode',
        'status', 'status_note', 'pickup_date', 'estimated_delivery_date', 'actual_delivery_date',
        'pod_signature', 'pod_photo', 'delivery_notes',
        'current_latitude', 'current_longitude', 'last_location_update',
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'declared_value' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'cod_charge' => 'decimal:2',
        'insurance_charge' => 'decimal:2',
        'total_charge' => 'decimal:2',
        'pickup_date' => 'datetime',
        'estimated_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'last_location_update' => 'datetime',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8'
    ];

    /* ==================== RELATIONSHIPS ==================== */

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function deliveryAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function trackings()
    {
        return $this->hasMany(ShipmentTracking::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function courier()
    {
        return $this->belongsTo(CourierPartner::class, 'courier_partner', 'name');
    }

    /* ==================== SCOPES ==================== */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInTransit($query)
    {
        return $query->whereIn('status', ['picked', 'in_transit', 'out_for_delivery']);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /* ==================== ACCESSORS ==================== */

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'picked' => 'info',
            'in_transit' => 'primary',
            'out_for_delivery' => 'secondary',
            'delivered' => 'success',
            'failed' => 'danger',
            'returned' => 'dark'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getFullAddressAttribute()
    {
        $address = $this->shipping_address;
        if ($this->landmark) {
            $address .= ', ' . $this->landmark;
        }
        $address .= ', ' . $this->city . ', ' . $this->state . ' - ' . $this->pincode;
        return $address;
    }

    public function getFormattedDeclaredValueAttribute()
    {
        return '₹ ' . number_format($this->declared_value, 2);
    }

    public function getFormattedTotalChargeAttribute()
    {
        return '₹ ' . number_format($this->total_charge, 2);
    }

    /* ==================== METHODS ==================== */

    public function updateTracking($status, $location = null, $remarks = null)
    {
        $this->trackings()->create([
            'status' => $status,
            'location' => $location,
            'remarks' => $remarks,
            'tracked_at' => now()
        ]);

        $this->status = $status;
        if ($status === 'delivered') {
            $this->actual_delivery_date = now();
        }
        $this->save();
    }

    public function generateShipmentNumber()
    {
        $prefix = 'SHIP';
        $year = date('Y');
        $month = date('m');
        $lastShipment = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastShipment ? intval(substr($lastShipment->shipment_number, -4)) + 1 : 1;

        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
