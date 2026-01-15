<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;


class LeaveController extends Controller

{
    public function __construct()
{
    $this->middleware('auth');
}

    /* =========================
       STAFF – APPLY LEAVE
    ==========================*/
    public function apply(Request $request)
    {
        // 🔒 Auth user
        $user = Auth::user();

        // 🔗 Employee mapping (MOST IMPORTANT)
        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Employee record not linked');
        }

        /* ================= VALIDATION ================= */
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'type'      => 'required|in:Paid,Unpaid,Sick,Half Day',
            'reason'    => 'required|string|max:255',
        ]);

        /* ================= HALF DAY RULE ================= */
        if ($request->type === 'Half Day' &&
            $request->from_date !== $request->to_date) {
            return back()->with(
                'error',
                '❌ Half Day leave must be for a single date only'
            );
        }

        /* ================= DUPLICATE / OVERLAP BLOCK ================= */
        $overlap = Leave::where('employee_id', $employee->id)
            ->where(function ($q) use ($request) {
                $q->whereBetween('from_date', [$request->from_date, $request->to_date])
                  ->orWhereBetween('to_date', [$request->from_date, $request->to_date])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('from_date', '<=', $request->from_date)
                         ->where('to_date', '>=', $request->to_date);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()->with(
                'error',
                '❌ Leave already applied for selected dates'
            );
        }

        /* ================= CREATE LEAVE ================= */
        Leave::create([
            'employee_id' => $employee->id,
            'from_date'   => $request->from_date,
            'to_date'     => $request->to_date,
            'type'        => $request->type,
            'reason'      => $request->reason,
            'status'      => 'Pending',
        ]);

        return back()->with('success', '✅ Leave applied successfully');
    }

    /* =========================
       ADMIN – VIEW LEAVES
    ==========================*/
    public function manage()
    {
        $leaves = Leave::with('employee')->latest()->get();
        return view('leaves.manage', compact('leaves'));
    }

    /* =========================
       ADMIN – APPROVE
    ==========================*/
  public function approve($id)
{
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    Leave::findOrFail($id)->update(['status' => 'Approved']);
    return back()->with('success', 'Leave approved');
}

    /* =========================
       ADMIN – REJECT
    ==========================*/
public function reject($id)
{
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    Leave::findOrFail($id)->update(['status' => 'Rejected']);
    return back()->with('success', 'Leave rejected');
}
 public function myLeaves()
    {
        $user = Auth::user();

        $employee = Employee::where('user_id', $user->id)->first();

        if (!$employee) {
            return back()->with('error', 'Employee record not linked');
        }

        $leaves = Leave::where('employee_id', $employee->id)
            ->latest()
            ->get();

        return view('leaves.my', compact('leaves'));
    }
}
