<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    /**
     * ================= MASS ASSIGNABLE =================
     */
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'working_hours',   // ✅ FIXED (no typo)
        'remarks',
        'status',
    ];

    /**
     * ================= CASTS =================
     */
    protected $casts = [
        'attendance_date' => 'date',
        'check_in'        => 'datetime:H:i:s',
        'check_out'       => 'datetime:H:i:s',
    ];

    /**
     * ================= RELATIONSHIPS =================
     */

    // Attendance belongs to an employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * ================= ACCESSORS =================
     */

    /**
     * Get working hours in HH:MM:SS format
     * - Uses DB value if present
     * - Auto-calculates for old records
     */
    public function getWorkingHoursAttribute($value)
    {
        // If already stored in DB, return it
        if (!empty($value)) {
            return $value;
        }

        // Calculate only if check-in & check-out exist
        if ($this->check_in && $this->check_out) {

            $checkIn  = Carbon::parse($this->check_in);
            $checkOut = Carbon::parse($this->check_out);

            $totalSeconds = $checkIn->diffInSeconds($checkOut);

            $hours   = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;

            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return null;
    }

    /**
     * ================= HELPERS =================
     */

    // Check if attendance is checked in
    public function isCheckedIn(): bool
    {
        return !is_null($this->check_in);
    }

    // Check if attendance is checked out
    public function isCheckedOut(): bool
    {
        return !is_null($this->check_out);
    }
}
