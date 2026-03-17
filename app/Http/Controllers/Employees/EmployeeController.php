<?php

namespace App\Http\Controllers\Employees;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\EmployeeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /* ================= SEND EMAIL ================= */
  public function sendEmail(Request $request, Employee $employee)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            // Use Auth facade instead of auth() helper
            $sender = Auth::user();

            // Send email using Laravel Mail
            Mail::to($employee->email)->send(new EmployeeEmail(
                $request->subject,
                $request->body,
                $sender
            ));

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again.'
            ], 500);
        }
    }

    /* ================= LIST ================= */
    public function index(Request $request)
    {
        $search  = $request->input('search');        // search text
        $perPage = $request->input('per_page', 10);  // show entries (default 10)

        $employees = Employee::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('employees.index', compact('employees', 'search', 'perPage'));
    }

    /* ================= SHOW ================= */
    public function show($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    /* ================= CREATE ================= */
    public function create()
    {
        return view('employees.create');
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
        ]);

        /* ===== CREATE USER ===== */
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'staff',
            'status'   => 1,
        ]);

        /* ===== EMPLOYEE CODE ===== */
        $code = 'EMP' . str_pad(Employee::count() + 1, 4, '0', STR_PAD_LEFT);

        /* ===== CREATE EMPLOYEE ===== */
        Employee::create([
            'user_id'       => $user->id,
            'employee_code' => $code,
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'department'    => $request->department,
            'joining_date'  => $request->joining_date,
            'status'        => 1,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully');
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $user = User::findOrFail($employee->user_id);

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        /* ===== UPDATE USER ===== */
        $userData = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // 🔐 password only if filled
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        /* ===== UPDATE EMPLOYEE ===== */
        $employee->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'department'   => $request->department,
            'joining_date' => $request->joining_date,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully');
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Optional: also delete user
        User::where('id', $employee->user_id)->delete();

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted');
    }
}
