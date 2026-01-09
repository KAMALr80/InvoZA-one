<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
    ];

    /**
     * Casts
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
