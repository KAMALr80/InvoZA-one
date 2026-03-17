<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryAgent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'agent_code', 'name', 'email', 'phone', 'alternate_phone',
        'address', 'city', 'state', 'pincode',
        'vehicle_type', 'vehicle_number', 'license_number',
        'aadhar_card', 'driving_license', 'photo',
        'bank_name', 'account_number', 'ifsc_code', 'upi_id',
        'employment_type', 'joining_date', 'salary', 'commission_type', 'commission_value',
        'service_areas', 'current_latitude', 'current_longitude', 'last_location_update',
        'is_active', 'status', 'total_deliveries', 'successful_deliveries', 'rating'
    ];

    protected $casts = [
        'service_areas' => 'array',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'commission_value' => 'decimal:2',
        'current_latitude' => 'decimal:8',
        'current_longitude' => 'decimal:8',
        'last_location_update' => 'datetime',
        'rating' => 'decimal:2'
    ];

    /* ==================== RELATIONSHIPS ==================== */

    /**
     * Get the user account associated with this delivery agent
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get shipments assigned to this delivery agent
     */
    public function assignedShipments()
    {
        return $this->hasMany(Shipment::class, 'assigned_to', 'user_id');
    }

    /* ==================== ACCESSORS ==================== */

    /**
     * Get full name with agent code
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->agent_code . ')';
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_deliveries > 0) {
            return round(($this->successful_deliveries / $this->total_deliveries) * 100, 2);
        }
        return 0;
    }

    /**
     * Get formatted salary
     */
    public function getFormattedSalaryAttribute()
    {
        return '₹ ' . number_format($this->salary, 2);
    }
}
