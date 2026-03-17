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
        'leave_id',
        'attendance_date',
        'check_in',
        'check_out',
        'working_hours',
        'remarks',
        'status',
        'marked_by',
        'is_auto_marked'
    ];

    /**
     * ================= CASTS =================
     */
    protected $casts = [
        'attendance_date' => 'date',
        'check_in'        => 'datetime:H:i:s',
        'check_out'       => 'datetime:H:i:s',
        'is_auto_marked'  => 'boolean',
    ];

    /**
     * ================= RELATIONSHIPS =================
     */

    // Attendance belongs to an employee
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Attendance may be linked to an approved leave
    public function leave()
    {
        return $this->belongsTo(Leave::class);
    }

    // Who marked this attendance (user)
    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
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
     * Safely get working hours in HH:MM format (for display – never throws exception).
     */
    public function getSafeWorkingHoursAttribute(): string
    {
        if (empty($this->working_hours)) {
            return '-';
        }

        // Check if it's a valid time format HH:MM:SS
        if (preg_match('/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/', $this->working_hours, $matches)) {
            return $matches[1] . ':' . $matches[2]; // return HH:MM
        }

        // If invalid, return raw string (or you could return '-')
        return $this->working_hours;
    }

    /**
     * Get working hours with 'h' and 'm' suffix (e.g., "2h 30m").
     */
    public function getFormattedWorkingHoursAttribute(): string
    {
        if (empty($this->working_hours)) {
            return '-';
        }

        if (preg_match('/^([0-9]{2}):([0-9]{2}):([0-9]{2})$/', $this->working_hours, $matches)) {
            $h = (int)$matches[1];
            $m = (int)$matches[2];
            return $h . 'h ' . $m . 'm';
        }

        // Fallback for invalid data
        return $this->working_hours;
    }

    /**
     * Get CSS class for status badge
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match (strtolower($this->status)) {
            'present'   => 'status-present',
            'absent'    => 'status-absent',
            'late'      => 'status-late',
            'half day'  => 'status-halfday',
            'leave'     => 'status-leave',
            default     => 'status-unknown',
        };
    }

    /**
     * Get status with emoji
     */
    public function getStatusWithEmojiAttribute(): string
    {
        return match (strtolower($this->status)) {
            'present'   => '✅ Present',
            'absent'    => '❌ Absent',
            'late'      => '⏰ Late',
            'half day'  => '⚠️ Half Day',
            'leave'     => '🏖️ Leave',
            default     => $this->status,
        };
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

    // Check if this record was auto-marked
    public function isAutoMarked(): bool
    {
        return $this->is_auto_marked;
    }

    // Scope for today's attendance
    public function scopeToday($query)
    {
        return $query->whereDate('attendance_date', today());
    }

    // Scope for a specific date
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    // Scope for a specific employee
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // Scope for status (case-insensitive)
    public function scopeOfStatus($query, $status)
    {
        return $query->whereRaw('LOWER(status) = ?', [strtolower($status)]);
    }
}
