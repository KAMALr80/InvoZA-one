<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    protected $fillable = [
        'shipment_id', 'status', 'location', 'latitude', 'longitude', 'remarks', 'updated_by', 'tracked_at'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'tracked_at' => 'datetime'
    ];

    /* ==================== RELATIONSHIPS ==================== */

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* ==================== ACCESSORS ==================== */

    public function getFormattedTrackedAtAttribute()
    {
        return $this->tracked_at->format('d M Y - h:i A');
    }

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
}
