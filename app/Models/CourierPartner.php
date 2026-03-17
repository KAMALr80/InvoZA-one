<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourierPartner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'api_url',
        'api_key',
        'api_secret',
        'api_config',
        'rate_card',
        'cod_charges',
        'serviceable_pincodes',
        'supported_services',
        'is_active',
        'priority'
    ];

    protected $casts = [
        'api_config' => 'array',
        'rate_card' => 'array',
        'cod_charges' => 'array',
        'serviceable_pincodes' => 'array',
        'supported_services' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get shipments for this courier partner
     */
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'courier_partner', 'name');
    }

    /**
     * Scope for active couriers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get rate for weight
     */
    public function getRateForWeight($weight)
    {
        if (!$this->rate_card) {
            return null;
        }

        $rates = $this->rate_card;
        ksort($rates); // Sort by weight

        $selectedRate = null;
        foreach ($rates as $maxWeight => $rate) {
            if ($weight <= $maxWeight) {
                $selectedRate = $rate;
                break;
            }
        }

        return $selectedRate ?? end($rates);
    }

    /**
     * Check if pincode is serviceable
     */
    public function isPincodeServiceable($pincode)
    {
        if (!$this->serviceable_pincodes) {
            return true; // Assume all pincodes if not specified
        }

        return in_array($pincode, $this->serviceable_pincodes);
    }
}
