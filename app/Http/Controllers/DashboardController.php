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

            $absentToday = max($totalEmployees - $presentToday, 0);


            /* ===== LOW STOCK ===== */
            $lowStockProducts = Product::where('quantity', '<=', 5)->get();

            /* =====================================================
               🤖 AI SALES PREDICTION
            ===================================================== */
            $aiSales = Sale::select('sale_date', 'grand_total')
                ->orderBy('sale_date')
                ->get()
                ->toJson();

            $aiCommand = 'py -3 ai/sales_prediction.py ' . escapeshellarg($aiSales);
            $aiOutput  = shell_exec($aiCommand);

            $aiPrediction = json_decode($aiOutput, true) ?? [
                'next_30_days_total' => 0,
                'daily_prediction_avg' => 0
            ];

            /* =====================================================
               📈 AI + PAST SALES COMBINED GRAPH DATA (NEW)
            ===================================================== */

            // Past 14 days sales
            $pastSales = Sale::select(
                    DB::raw('DATE(sale_date) as date'),
                    DB::raw('SUM(grand_total) as total')
                )
                ->whereDate('sale_date', '>=', Carbon::now()->subDays(13))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $pastLabels = $pastSales->pluck('date')->map(fn ($d) =>
                Carbon::parse($d)->format('d M')
            );

            $pastData = $pastSales->pluck('total');

            // AI future 30 days (daily average based)
            $futureLabels = collect(range(1, 30))->map(fn ($d) =>
                Carbon::now()->addDays($d)->format('d M')
            );

            $futureDaily = $aiPrediction['daily_prediction_avg'] ?? 0;
            $futureData  = collect(range(1, 30))->map(fn () => $futureDaily);

           return view('dashboard.admin', compact(
    'totalProducts',
    'totalRevenue',
    'todaySales',
    'totalTransactions',
    'averageSale',
    'totalEmployees',
    'presentToday',
    'absentToday',
    'lowStockProducts',
    'aiPrediction',
    'pastLabels',
    'pastData',
    'futureLabels',
    'futureData'
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
