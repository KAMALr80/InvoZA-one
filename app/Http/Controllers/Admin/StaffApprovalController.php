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
public function approve($id)
{
    $user = User::findOrFail($id);

    $lastEmployee = Employee::latest('id')->first();
    $nextNumber = $lastEmployee ? $lastEmployee->id + 1 : 1;

    $employeeCode = 'EMP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    Employee::create([
        'user_id'       => $user->id,
        'name'          => $user->name,
        'email'         => $user->email,
        'employee_code' => $employeeCode, // ✅ REQUIRED
    ]);

    return back()->with('success', 'Staff approved successfully');
}
}
