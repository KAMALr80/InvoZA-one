<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /* ================= STAFF ================= */

    public function myAttendance()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', today())
            ->first();

        $history = Attendance::where('employee_id', $employee->id)
            ->orderBy('attendance_date', 'desc')
            ->paginate(10);

        return view('attendance.my', compact(
            'employee',
            'todayAttendance',
            'history'
        ));
    }

    public function checkIn()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $now = now();

        // Office timing
        $officeStart = Carbon::createFromTime(15, 0);  // 03:00 PM
        $lateTime    = Carbon::createFromTime(15, 15); // 03:15 PM

        $remarks = null;

        if ($now->gt($lateTime)) {
            $remarks = 'Late Check-in';
        }

        Attendance::updateOrCreate(
            [
                'employee_id'     => $employee->id,
                'attendance_date' => Carbon::today()->toDateString(),
            ],
            [
                'check_in' => $now->format('H:i:s'),
                'status'   => 'Present',   // ✅ ALWAYS ENUM SAFE
                'remarks'  => $remarks,
            ]
        );

        return back()->with('success', 'Check-in successful at '.$now->format('h:i A'));
    }

    public function checkOut()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', today())
            ->firstOrFail();

        $checkIn  = Carbon::parse($attendance->check_in);
        $checkOut = now();

        $minutes = $checkIn->diffInMinutes($checkOut);

        $remarks = $attendance->remarks;

        if ($minutes < 240) {
            $remarks = 'Half Day (worked less than 4 hours)';
        }

        $attendance->update([
            'check_out'        => $checkOut->format('H:i:s'),
            'working_minutes' => $minutes,
            'status'           => 'Present', // ✅ NEVER Half Day here
            'remarks'          => $remarks,
        ]);

        return back()->with('success', 'Check-out successful at '.$checkOut->format('h:i A'));
    }

    /* ================= ADMIN ================= */

    public function manage()
    {
        $records = Attendance::with(['employee.user'])
            ->orderBy('attendance_date', 'desc')
            ->paginate(20);

        return view('attendance.manage', compact('records'));
    }
}
