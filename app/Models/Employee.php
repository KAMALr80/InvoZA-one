<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'user_id',
        'employee_code',
        'name',
        'email',
        'phone',
        'department',
        'joining_date',
        'status',
    ];

    /**
     * ================= RELATIONSHIPS =================
     */

    // Employee belongs to a User (login account)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Employee has many attendance records
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * ================= HELPERS (OPTIONAL BUT USEFUL) =================
     */

    // Get today's attendance
    public function todayAttendance()
    {
        return $this->attendances()
            ->where('attendance_date', today())
            ->first();
    }
}
