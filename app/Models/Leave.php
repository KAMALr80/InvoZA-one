<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'leave_number',
        'leave_type',
        'duration_type',
        'from_date',
        'to_date',
        'total_days',
        'session',
        'start_time',
        'end_time',
        'reason',
        'contact_number',
        'handover_notes',
        'document_path',
        'status',
        'approved_by',
        'approved_at',
        'approval_remarks',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'cancelled_by',
        'cancelled_at',
        'leave_balance_before',
        'leave_balance_after',
        'applied_on'
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'applied_on' => 'datetime',
        'total_days' => 'decimal:2',
        'leave_balance_before' => 'decimal:2',
        'leave_balance_after' => 'decimal:2'
    ];

    protected $attributes = [
        'status' => 'Pending',
        'duration_type' => 'full_day'
    ];

    /* ==================== RELATIONSHIPS ==================== */

    /**
     * Get the employee who requested leave
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who approved this leave
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who rejected this leave
     */
    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the user who cancelled this leave
     */
    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /* ==================== ACCESSORS ==================== */

    public function getFormattedFromDateAttribute()
    {
        return Carbon::parse($this->from_date)->format('d M, Y');
    }

    public function getFormattedToDateAttribute()
    {
        return Carbon::parse($this->to_date)->format('d M, Y');
    }

    public function getLeaveTypeLabelAttribute()
    {
        $labels = [
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'casual' => 'Casual Leave',
            'unpaid' => 'Unpaid Leave',
            'maternity' => 'Maternity Leave',
            'paternity' => 'Paternity Leave',
            'bereavement' => 'Bereavement Leave',
            'study' => 'Study Leave',
            'half_day' => 'Half Day',
            'short_leave' => 'Short Leave'
        ];
        return $labels[$this->leave_type] ?? ucfirst(str_replace('_', ' ', $this->leave_type));
    }

    public function getDurationLabelAttribute()
    {
        $labels = [
            'full_day' => 'Full Day',
            'half_day' => 'Half Day',
            'short_leave' => 'Short Leave'
        ];
        return $labels[$this->duration_type] ?? ucfirst(str_replace('_', ' ', $this->duration_type));
    }

    /* ==================== STATUS CHECKS ==================== */

    public function isPending()
    {
        return $this->status === 'Pending';
    }

    public function isApproved()
    {
        return $this->status === 'Approved';
    }

    public function isRejected()
    {
        return $this->status === 'Rejected';
    }

    public function isCancelled()
    {
        return $this->status === 'Cancelled';
    }

    /* ==================== SCOPES ==================== */

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForYear($query, $year)
    {
        return $query->whereYear('from_date', $year);
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('from_date', [$from, $to]);
    }
}
