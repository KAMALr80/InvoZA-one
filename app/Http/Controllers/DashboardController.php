<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /* =======================
           COMMON DATA
        ======================= */
        $totalProducts = Product::count();
        $totalRevenue  = Sale::sum('grand_total');
        $todaySales    = Sale::whereDate('sale_date', today())->sum('grand_total');
        $totalTransactions = Sale::count();
        $averageSale = $totalTransactions > 0
            ? $totalRevenue / $totalTransactions
            : 0;

        /* =======================
           ADMIN DASHBOARD
        ======================= */
        if ($user->role === 'admin') {

            /* ===== EMPLOYEES ===== */
            $totalEmployees = Employee::count();

            $presentToday = Attendance::whereDate('attendance_date', today())
                ->where('status', 'Present')
                ->count();

            // Absent = total - present (BEST PRACTICE)
            $absentToday = max($totalEmployees - $presentToday, 0);

            /* ===== SALES CHART (LAST 7 DAYS) ===== */
            $sales = Sale::select(
                    DB::raw('DATE(sale_date) as date'),
                    DB::raw('SUM(grand_total) as total')
                )
                ->whereDate('sale_date', '>=', Carbon::now()->subDays(6))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $salesLabels = $sales->pluck('date')->map(function ($d) {
                return Carbon::parse($d)->format('d M');
            });

            $salesData = $sales->pluck('total');

            /* ===== LOW STOCK ===== */
            $lowStockProducts = Product::where('quantity', '<=', 5)->get();

            return view('dashboard.admin', compact(
                'totalProducts',
                'totalRevenue',
                'todaySales',
                'totalTransactions',
                'averageSale',
                'totalEmployees',
                'presentToday',
                'absentToday',
                'salesLabels',   // ✅ NOW PASSED
                'salesData',     // ✅ NOW PASSED
                'lowStockProducts'
            ));
        }

        /* =======================
           STAFF DASHBOARD
        ======================= */
        if ($user->role === 'staff') {

            $employee = Employee::where('user_id', $user->id)->first();

            $myAttendanceToday = Attendance::where('employee_id', $employee->id ?? null)
                ->whereDate('attendance_date', today())
                ->first();

            return view('dashboard.staff', compact(
                'totalProducts',
                'todaySales',
                'myAttendanceToday'
            ));
        }

        /* =======================
           DEFAULT (HR / OTHERS)
        ======================= */
        return view('dashboard.staff', compact(
            'totalProducts',
            'todaySales'
        ));
    }
}
