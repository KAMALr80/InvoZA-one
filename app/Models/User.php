<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ==================== RELATIONSHIPS ==================== */

    /**
     * Get the employee associated with this user
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get sales created by this user
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'created_by');
    }

    /**
     * Get purchases created by this user
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'user_id');
    }

    /**
     * Get shipments created by this user
     */
    public function createdShipments()
    {
        return $this->hasMany(Shipment::class, 'created_by');
    }

    /**
     * Get shipments assigned to this user (as delivery agent)
     */
    public function assignedShipments()
    {
        return $this->hasMany(Shipment::class, 'assigned_to');
    }

    /**
     * Get attendance marked by this user
     */
    public function markedAttendances()
    {
        return $this->hasMany(Attendance::class, 'marked_by');
    }

    /**
     * Get leaves approved by this user
     */
    public function approvedLeaves()
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }

    /**
     * Get leaves rejected by this user
     */
    public function rejectedLeaves()
    {
        return $this->hasMany(Leave::class, 'rejected_by');
    }

    /* ==================== ACCESSORS ==================== */

    /**
     * Get display name with role
     */
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . ucfirst($this->role) . ')';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is HR
     */
    public function isHr()
    {
        return $this->role === 'hr';
    }

    /**
     * Check if user is staff
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
