<?php
namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /* ================= STAFF ================= */

    public function myLeaves()
    {
        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        $leaves = Leave::where('employee_id', $employee->id)
            ->orderBy('created_at','desc')
            ->get();

        return view('leaves.my', compact('leaves'));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'type'      => 'required',
            'reason'    => 'nullable'
        ]);

        $employee = Employee::where('user_id', Auth::id())->firstOrFail();

        Leave::create([
            'employee_id' => $employee->id,
            'from_date'   => $request->from_date,
            'to_date'     => $request->to_date,
            'type'        => $request->type,
            'reason'      => $request->reason,
        ]);

        return back()->with('success','Leave applied successfully');
    }

    /* ================= ADMIN / HR ================= */

    public function manage()
    {
        $leaves = Leave::with('employee')->orderBy('created_at','desc')->get();
        return view('leaves.manage', compact('leaves'));
    }

    public function approve($id)
    {
        Leave::where('id',$id)->update(['status'=>'Approved']);
        return back()->with('success','Leave approved');
    }

    public function reject($id)
    {
        Leave::where('id',$id)->update(['status'=>'Rejected']);
        return back()->with('success','Leave rejected');
    }
}
