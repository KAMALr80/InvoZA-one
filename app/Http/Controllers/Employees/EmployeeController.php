<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /* ================= LIST ================= */

    public function index(Request $request)
    {
        $search = $request->search;

        $employees = Employee::when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('employee_code', 'like', "%$search%")
                  ->orWhere('department', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact('employees'));
    }

    /* ================= CREATE ================= */

    public function create()
    {
        // Users list (for mapping employee ↔ user)
        $users = User::whereDoesntHave('employee')->get();

        return view('employees.create', compact('users'));
    }

    /* ================= STORE ================= */


public function store(Request $request)
{
    $request->validate([
        'name'  => 'required',
        'email' => 'required|email|unique:employees',
    ]);

    $code = 'EMP' . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT);

    Employee::create([
        'user_id'        => Auth::id(),   // ✅ AUTO LINK
        'employee_code'  => $code,
        'name'           => $request->name,
        'email'          => $request->email,
        'phone'          => $request->phone,
        'department'     => $request->department,
        'joining_date'   => $request->joining_date,
        'status'         => 1,
    ]);

    return redirect('/employees')->with('success', 'Employee added');
}
    /* ================= EDIT ================= */

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $users = User::all();

        return view('employees.edit', compact('employee','users'));
    }

    /* ================= UPDATE ================= */

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:employees,email,' . $id,
            'user_id' => 'nullable|exists:users,id',
        ]);

        $employee->update([
            'user_id'       => $request->user_id,
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'department'    => $request->department,
            'joining_date'  => $request->joining_date,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated');
    }

    /* ================= DELETE ================= */

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted');
    }
}
