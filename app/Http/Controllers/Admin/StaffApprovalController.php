<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;

class StaffApprovalController extends Controller
{
    public function index()
    {
        $staff = User::where('role', 'staff')
                     ->where('status', 'pending')
                     ->get();

        return view('admin.staff-approvals', compact('staff'));
    }

      private function generateEmployeeCode()
    {
        $lastEmployee = Employee::orderBy('id', 'desc')->first();

        if ($lastEmployee && $lastEmployee->employee_code) {
            // EMP0007 → 7
            $lastNumber = (int) substr($lastEmployee->employee_code, 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'EMP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
public function approve($userId)
{
    $user = User::findOrFail($userId);

    // Already approved safety
    if ($user->status === 'approved') {
        return redirect()->back()->with('info', 'Already approved');
    }

    // Duplicate employee check
    $exists = Employee::where('email', $user->email)->exists();

    if ($exists) {
        return redirect()->back()->with('error', 'Employee with this email already exists.');
    }

    // Create employee
    Employee::create([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'employee_code' => $this->generateEmployeeCode(),
    ]);

    // ✅ MOST IMPORTANT LINE
    $user->status = 'approved';
    $user->save();

    return redirect()->back()->with('success', 'Employee approved successfully');
}

}
