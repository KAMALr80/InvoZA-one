<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Main Dashboard
     */
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
        if(Auth::check() && Auth::user()->role == 'admin') {
            /* ===== EMPLOYEES ===== */
            $totalEmployees = Employee::count();

            // Present count
            $presentToday = Attendance::whereDate('attendance_date', today())
                ->where(function($query) {
                    $query->where('status', 'present')
                          ->orWhere('status', 'Present');
                })
                ->count();

            // Absent count
            $absentToday = Attendance::whereDate('attendance_date', today())
                ->where(function($query) {
                    $query->where('status', 'absent')
                          ->orWhere('status', 'Absent');
                })
                ->count();

            // Late count
            $lateToday = Attendance::whereDate('attendance_date', today())
                ->where(function($query) {
                    $query->where('status', 'late')
                          ->orWhere('status', 'Late');
                })
                ->count();

            // Half Day count
            $halfDayToday = Attendance::whereDate('attendance_date', today())
                ->where('status', 'Half Day')
                ->count();

            // Not Marked = Total Employees - (Present + Absent + Late + Half Day)
            $markedCount = $presentToday + $absentToday + $lateToday + $halfDayToday;
            $notMarkedToday = max(0, $totalEmployees - $markedCount);

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
               📈 AI + PAST SALES COMBINED GRAPH DATA
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

            // Attendance data for chart
            $attendanceData = [
                'labels' => ['Present', 'Absent', 'Late', 'Half Day', 'Not Marked'],
                'data' => [$presentToday, $absentToday, $lateToday, $halfDayToday, $notMarkedToday],
                'colors' => ['#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#6b7280'],
                'total' => $totalEmployees,
                'marked' => $markedCount,
                'percentage' => $totalEmployees > 0 ? round(($markedCount/$totalEmployees)*100) : 0
            ];

            return view('dashboard.admin', compact(
                'totalProducts',
                'totalRevenue',
                'todaySales',
                'totalTransactions',
                'averageSale',
                'totalEmployees',
                'presentToday',
                'absentToday',
                'lateToday',
                'halfDayToday',
                'notMarkedToday', // ✅ यह variable अब available है
                'lowStockProducts',
                'aiPrediction',
                'pastLabels',
                'pastData',
                'futureLabels',
                'futureData',
                'attendanceData'
            ));
        }

        /* =======================
           HR DASHBOARD
        ======================= */
        if(Auth::check() && Auth::user()->role == 'hr') {
            /* ===== HR SPECIFIC DATA ===== */
            $totalEmployees = Employee::count();

            // Present count
            $presentToday = Attendance::whereDate('attendance_date', today())
                ->where(function($query) {
                    $query->where('status', 'present')
                          ->orWhere('status', 'Present');
                })
                ->count();

            // Absent count
            $absentToday = Attendance::whereDate('attendance_date', today())
                ->where(function($query) {
                    $query->where('status', 'absent')
                          ->orWhere('status', 'Absent');
                })
                ->count();

            // Late count
            $lateToday = Attendance::whereDate('attendance_date', today())
                ->where(function($query) {
                    $query->where('status', 'late')
                          ->orWhere('status', 'Late');
                })
                ->count();

            // Half Day count
            $halfDayToday = Attendance::whereDate('attendance_date', today())
                ->where('status', 'Half Day')
                ->count();

            // Not Marked
            $markedCount = $presentToday + $absentToday + $lateToday + $halfDayToday;
            $notMarkedToday = max(0, $totalEmployees - $markedCount);

            // Get pending leaves count
            $pendingLeaves = Leave::where('status', 'pending')->count();

            // Get employees on leave today
            $onLeaveToday = $this->getOnLeaveTodaySimple();

            // Get today's attendance for HR to mark
            $todayAttendance = Attendance::whereDate('attendance_date', today())
                ->with('employee')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Get employees without attendance for today
            $employeesWithoutAttendance = Employee::whereDoesntHave('attendances', function($query) {
                $query->whereDate('attendance_date', today());
            })->get();

            // Get recent employees (last 5)
            $recentEmployees = Employee::latest()->take(5)->get();

            // Get recent leave requests
            $recentLeaves = Leave::with('employee')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            // Get department statistics
            $departmentStats = Employee::select('department', DB::raw('count(*) as count'))
                ->whereNotNull('department')
                ->groupBy('department')
                ->orderBy('count', 'desc')
                ->get()
                ->map(function($dept) {
                    // Assign colors and icons based on department
                    $colors = [
                        'IT' => '#3b82f6',
                        'Sales' => '#10b981',
                        'Marketing' => '#8b5cf6',
                        'HR' => '#f59e0b',
                        'Finance' => '#ef4444',
                        'Operations' => '#06b6d4',
                        'Engineering' => '#6366f1',
                        'Management' => '#8b5cf6',
                        'Support' => '#3b82f6',
                        'Production' => '#10b981',
                    ];

                    $icons = [
                        'IT' => '💻',
                        'Sales' => '💰',
                        'Marketing' => '📢',
                        'HR' => '👥',
                        'Finance' => '📊',
                        'Operations' => '⚙️',
                        'Engineering' => '🔧',
                        'Management' => '👔',
                        'Support' => '🛟',
                        'Production' => '🏭',
                    ];

                    return [
                        'name' => $dept->department,
                        'count' => $dept->count,
                        'color' => $colors[$dept->department] ?? '#6b7280',
                        'icon' => $icons[$dept->department] ?? '👤'
                    ];
                });

            // Get weekly attendance trend
            $attendanceTrend = $this->getWeeklyAttendanceTrend();

            // Calculate attendance percentage
            $attendancePercentage = $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100, 1) : 0;

            // Get employee status statistics
            $activeEmployees = Employee::where('status', 'active')->count();
            $inactiveEmployees = Employee::where('status', 'inactive')->count();

            // Get recent attendance issues
            $attendanceIssues = Attendance::whereDate('attendance_date', today())
                ->where(function($query) {
                    $query->where('remarks', 'like', '%late%')
                          ->orWhere('remarks', 'like', '%early%')
                          ->orWhere('remarks', 'like', '%absent%');
                })
                ->count();

            // Attendance data for chart
            $attendanceData = [
                'labels' => ['Present', 'Absent', 'Late', 'Half Day', 'Not Marked'],
                'data' => [$presentToday, $absentToday, $lateToday, $halfDayToday, $notMarkedToday],
                'colors' => ['#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#6b7280']
            ];

            return view('dashboard.hr_main', compact(
                'totalEmployees',
                'presentToday',
                'absentToday',
                'lateToday',
                'halfDayToday',
                'notMarkedToday', // ✅ यह variable अब available है
                'pendingLeaves',
                'onLeaveToday',
                'todayAttendance',
                'employeesWithoutAttendance',
                'recentEmployees',
                'recentLeaves',
                'departmentStats',
                'attendanceTrend',
                'attendancePercentage',
                'activeEmployees',
                'inactiveEmployees',
                'attendanceIssues',
                'totalProducts',
                'todaySales',
                'attendanceData'
            ));
        }

        /*  =======================
           STAFF DASHBOARD
        ======================= */
        if(Auth::check() && Auth::user()->role == 'staff') {
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
           DEFAULT (GUEST / OTHERS)
        ======================= */
        return view('dashboard.staff', compact(
            'totalProducts',
            'todaySales'
        ));
    }

    /**
     * HR Dashboard - For admin and hr users
     */
    public function hrDashboard()
    {
        // Allow both 'admin' and 'hr' roles
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'hr')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access to HR Dashboard');
        }

        // Get total employees count
        $totalEmployees = Employee::count();

        // Get today's attendance counts
        $presentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'present')
                      ->orWhere('status', 'Present');
            })
            ->count();

        $absentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'absent')
                      ->orWhere('status', 'Absent');
            })
            ->count();

        $lateToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'late')
                      ->orWhere('status', 'Late');
            })
            ->count();

        $halfDayToday = Attendance::whereDate('attendance_date', today())
            ->where('status', 'Half Day')
            ->count();

        // Not Marked
        $markedCount = $presentToday + $absentToday + $lateToday + $halfDayToday;
        $notMarkedToday = max(0, $totalEmployees - $markedCount);

        // Get pending leaves count
        $pendingLeaves = Leave::where('status', 'pending')->count();

        // Get employees on leave today
        $onLeaveToday = $this->getOnLeaveTodaySimple();

        // Get today's attendance for HR to mark
        $todayAttendance = Attendance::whereDate('attendance_date', today())
            ->with('employee')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get employees without attendance for today
        $employeesWithoutAttendance = Employee::whereDoesntHave('attendances', function($query) {
            $query->whereDate('attendance_date', today());
        })->get();

        // Get recent employees (last 5)
        $recentEmployees = Employee::latest()->take(5)->get();

        // Get recent leave requests
        $recentLeaves = Leave::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Get department statistics
        $departmentStats = Employee::select('department', DB::raw('count(*) as count'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('count', 'desc')
            ->get()
            ->map(function($dept) {
                // Assign colors and icons based on department
                $colors = [
                    'IT' => '#3b82f6',
                    'Sales' => '#10b981',
                    'Marketing' => '#8b5cf6',
                    'HR' => '#f59e0b',
                    'Finance' => '#ef4444',
                    'Operations' => '#06b6d4',
                    'Engineering' => '#6366f1',
                    'Management' => '#8b5cf6',
                    'Support' => '#3b82f6',
                    'Production' => '#10b981',
                ];

                $icons = [
                    'IT' => '💻',
                    'Sales' => '💰',
                    'Marketing' => '📢',
                    'HR' => '👥',
                    'Finance' => '📊',
                    'Operations' => '⚙️',
                    'Engineering' => '🔧',
                    'Management' => '👔',
                    'Support' => '🛟',
                    'Production' => '🏭',
                ];

                return [
                    'name' => $dept->department,
                    'count' => $dept->count,
                    'color' => $colors[$dept->department] ?? '#6b7280',
                    'icon' => $icons[$dept->department] ?? '👤'
                ];
            });

        // Get weekly attendance trend
        $attendanceTrend = $this->getWeeklyAttendanceTrend();

        // Get employee turnover rate
        $turnoverRate = $this->getEmployeeTurnover();

        // Upcoming holidays
        $upcomingHolidays = [
            [
                'name' => 'New Year\'s Day',
                'date' => Carbon::now()->addDays(15),
                'days_until' => 15,
                'icon' => '🎆'
            ],
            [
                'name' => 'Republic Day',
                'date' => Carbon::now()->addDays(30),
                'days_until' => 30,
                'icon' => '🇮🇳'
            ],
            [
                'name' => 'Holi',
                'date' => Carbon::now()->addDays(45),
                'days_until' => 45,
                'icon' => '🎨'
            ],
            [
                'name' => 'Diwali',
                'date' => Carbon::now()->addDays(120),
                'days_until' => 120,
                'icon' => '🪔'
            ]
        ];

        // Get gender statistics if column exists
        $genderStats = [];
        if (Schema::hasColumn('employees', 'gender')) {
            $genderStats = Employee::select('gender', DB::raw('count(*) as count'))
                ->whereNotNull('gender')
                ->groupBy('gender')
                ->get();
        }

        // Calculate attendance percentage
        $attendancePercentage = $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100, 1) : 0;

        // Get employee status statistics
        $activeEmployees = Employee::where('status', 'active')->count();
        $inactiveEmployees = Employee::where('status', 'inactive')->count();

        // Get recent attendance issues (late arrivals, early departures)
        $attendanceIssues = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('remarks', 'like', '%late%')
                      ->orWhere('remarks', 'like', '%early%')
                      ->orWhere('remarks', 'like', '%absent%');
            })
            ->count();

        // Get monthly leave statistics
        $monthlyLeaves = Leave::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Attendance data for chart
        $attendanceData = [
            'labels' => ['Present', 'Absent', 'Late', 'Half Day', 'Not Marked'],
            'data' => [$presentToday, $absentToday, $lateToday, $halfDayToday, $notMarkedToday],
            'colors' => ['#10b981', '#ef4444', '#f59e0b', '#8b5cf6', '#6b7280']
        ];

        return view('dashboard.hr', compact(
            'totalEmployees',
            'presentToday',
            'absentToday',
            'lateToday',
            'halfDayToday',
            'notMarkedToday', // ✅ यह variable अब available है
            'pendingLeaves',
            'onLeaveToday',
            'todayAttendance',
            'employeesWithoutAttendance',
            'recentEmployees',
            'recentLeaves',
            'departmentStats',
            'upcomingHolidays',
            'attendanceTrend',
            'turnoverRate',
            'genderStats',
            'attendancePercentage',
            'activeEmployees',
            'inactiveEmployees',
            'attendanceIssues',
            'monthlyLeaves',
            'attendanceData'
        ));
    }

    /**
     * Get weekly attendance trend
     */
    private function getWeeklyAttendanceTrend()
    {
        $days = [];
        $presentData = [];
        $absentData = [];
        $lateData = [];
        $halfDayData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('D');

            $present = Attendance::whereDate('attendance_date', $date)
                ->where(function($query) {
                    $query->where('status', 'present')
                          ->orWhere('status', 'Present');
                })
                ->count();

            $absent = Attendance::whereDate('attendance_date', $date)
                ->where(function($query) {
                    $query->where('status', 'absent')
                          ->orWhere('status', 'Absent');
                })
                ->count();

            $late = Attendance::whereDate('attendance_date', $date)
                ->where(function($query) {
                    $query->where('status', 'late')
                          ->orWhere('status', 'Late');
                })
                ->count();

            $halfDay = Attendance::whereDate('attendance_date', $date)
                ->where('status', 'Half Day')
                ->count();

            $presentData[] = $present;
            $absentData[] = $absent;
            $lateData[] = $late;
            $halfDayData[] = $halfDay;
        }

        return [
            'labels' => $days,
            'present' => $presentData,
            'absent' => $absentData,
            'late' => $lateData,
            'half_day' => $halfDayData,
            'present_total' => array_sum($presentData),
            'absent_total' => array_sum($absentData),
            'late_total' => array_sum($lateData),
            'half_day_total' => array_sum($halfDayData)
        ];
    }

    /**
     * Get employee turnover rate
     */
    private function getEmployeeTurnover()
    {
        $totalEmployees = Employee::count();

        if ($totalEmployees == 0) {
            return 0;
        }

        // Check if employees table has deleted_at column (soft deletes)
        if (Schema::hasColumn('employees', 'deleted_at')) {
            $employeesLeftThisMonth = Employee::onlyTrashed()
                ->whereMonth('deleted_at', now()->month)
                ->whereYear('deleted_at', now()->year)
                ->count();
        }
        // Check if employees table has is_active column
        elseif (Schema::hasColumn('employees', 'is_active')) {
            $employeesLeftThisMonth = Employee::where('is_active', false)
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count();
        }
        // Check if employees table has status column
        elseif (Schema::hasColumn('employees', 'status')) {
            $employeesLeftThisMonth = Employee::where('status', 'inactive')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count();
        }
        else {
            // Default: no turnover data available
            $employeesLeftThisMonth = 0;
        }

        $turnoverRate = ($employeesLeftThisMonth / $totalEmployees) * 100;

        return round($turnoverRate, 2);
    }

    /**
     * Get employees on leave today (simplified)
     */
    private function getOnLeaveTodaySimple()
    {
        try {
            // First check if leaves table exists
            if (!Schema::hasTable('leaves')) {
                return 0;
            }

            // Try different column combinations for date ranges
            if (Schema::hasColumn('leaves', 'from_date') && Schema::hasColumn('leaves', 'to_date')) {
                return Leave::where('status', 'approved')
                    ->whereDate('from_date', '<=', today())
                    ->whereDate('to_date', '>=', today())
                    ->count();
            }

            if (Schema::hasColumn('leaves', 'leave_date')) {
                return Leave::where('status', 'approved')
                    ->whereDate('leave_date', today())
                    ->count();
            }

            if (Schema::hasColumn('leaves', 'date')) {
                return Leave::where('status', 'approved')
                    ->whereDate('date', today())
                    ->count();
            }

            // Fallback: count leaves approved today
            return Leave::where('status', 'approved')
                ->whereDate('created_at', today())
                ->count();

        } catch (\Exception $e) {
            Log::error('Error in getOnLeaveTodaySimple: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get employees with upcoming birthdays (next 30 days)
     */
    private function getUpcomingBirthdays()
    {
        if (!Schema::hasColumn('employees', 'date_of_birth')) {
            return collect([]);
        }

        $today = Carbon::today();
        $nextMonth = Carbon::today()->addDays(30);

        return Employee::whereNotNull('date_of_birth')
            ->whereRaw("DATE_FORMAT(date_of_birth, '%m-%d') BETWEEN ? AND ?",
                [$today->format('m-d'), $nextMonth->format('m-d')])
            ->orderByRaw("DATE_FORMAT(date_of_birth, '%m-%d')")
            ->take(5)
            ->get();
    }

    /**
     * Get employees with work anniversaries (next 30 days)
     */
    private function getUpcomingAnniversaries()
    {
        if (!Schema::hasColumn('employees', 'joining_date')) {
            return collect([]);
        }

        $today = Carbon::today();
        $nextMonth = Carbon::today()->addDays(30);

        return Employee::whereNotNull('joining_date')
            ->whereRaw("DATE_FORMAT(joining_date, '%m-%d') BETWEEN ? AND ?",
                [$today->format('m-d'), $nextMonth->format('m-d')])
            ->orderByRaw("DATE_FORMAT(joining_date, '%m-%d')")
            ->take(5)
            ->get();
    }

    /**
     * Common dashboard data for quick access
     */
    public function getCommonStats()
    {
        $totalProducts = Product::count();
        $totalRevenue = Sale::sum('grand_total');
        $todaySales = Sale::whereDate('sale_date', today())->sum('grand_total');
        $totalEmployees = Employee::count();

        return [
            'total_products' => $totalProducts,
            'total_revenue' => $totalRevenue,
            'today_sales' => $todaySales,
            'total_employees' => $totalEmployees,
            'date' => now()->format('l, F j, Y')
        ];
    }

    /**
     * Get HR analytics data (for AJAX requests)
     */
    public function getHrAnalytics(Request $request)
    {
        // Allow both admin and hr roles
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'hr')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $totalEmployees = Employee::count();
        $presentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'present')
                      ->orWhere('status', 'Present');
            })
            ->count();

        $absentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'absent')
                      ->orWhere('status', 'Absent');
            })
            ->count();

        $lateToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'late')
                      ->orWhere('status', 'Late');
            })
            ->count();

        $halfDayToday = Attendance::whereDate('attendance_date', today())
            ->where('status', 'Half Day')
            ->count();

        $markedCount = $presentToday + $absentToday + $lateToday + $halfDayToday;
        $notMarkedToday = max(0, $totalEmployees - $markedCount);

        $data = [
            'total_employees' => $totalEmployees,
            'present_today' => $presentToday,
            'absent_today' => $absentToday,
            'late_today' => $lateToday,
            'half_day_today' => $halfDayToday,
            'not_marked_today' => $notMarkedToday,
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'on_leave_today' => $this->getOnLeaveTodaySimple(),
            'attendance_percentage' => $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100, 1) : 0,
            'timestamp' => now()->toDateTimeString()
        ];

        return response()->json($data);
    }

    /**
     * Calculate overall attendance percentage
     */
    private function calculateAttendancePercentage()
    {
        $totalEmployees = Employee::count();

        if ($totalEmployees == 0) {
            return 0;
        }

        $presentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'present')
                      ->orWhere('status', 'Present');
            })
            ->count();

        return round(($presentToday / $totalEmployees) * 100, 1);
    }

    /**
     * Get department-wise employee count
     */
    public function getDepartmentStats()
    {
        // Allow both admin and hr roles
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'hr')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = Employee::select('department', DB::raw('count(*) as count'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json($stats);
    }

    /**
     * Get monthly attendance summary
     */
    public function getMonthlyAttendance(Request $request)
    {
        // Allow both admin and hr roles
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'hr')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $data = Attendance::select(
                DB::raw('DATE(attendance_date) as date'),
                DB::raw('SUM(CASE WHEN status IN ("present", "Present") THEN 1 ELSE 0 END) as present'),
                DB::raw('SUM(CASE WHEN status IN ("absent", "Absent") THEN 1 ELSE 0 END) as absent'),
                DB::raw('SUM(CASE WHEN status IN ("late", "Late") THEN 1 ELSE 0 END) as late'),
                DB::raw('SUM(CASE WHEN status = "Half Day" THEN 1 ELSE 0 END) as half_day')
            )
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }

    /**
     * HR Attendance Marking Page
     */
    public function hrAttendanceMark()
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'hr')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        // Get all employees
        $employees = Employee::where('status', 'active')->get();

        // Get today's attendance
        $todayAttendance = Attendance::whereDate('attendance_date', today())
            ->with('employee')
            ->get()
            ->keyBy('employee_id');

        // Get attendance status counts
        $totalEmployees = Employee::count();
        $presentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'present')
                      ->orWhere('status', 'Present');
            })
            ->count();

        $absentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'absent')
                      ->orWhere('status', 'Absent');
            })
            ->count();

        $lateToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'late')
                      ->orWhere('status', 'Late');
            })
            ->count();

        $halfDayToday = Attendance::whereDate('attendance_date', today())
            ->where('status', 'Half Day')
            ->count();

        $markedCount = $presentToday + $absentToday + $lateToday + $halfDayToday;
        $notMarkedToday = max(0, $totalEmployees - $markedCount);

        $attendanceCounts = [
            'present' => $presentToday,
            'absent' => $absentToday,
            'late' => $lateToday,
            'half_day' => $halfDayToday,
            'not_marked' => $notMarkedToday,
            'pending' => $notMarkedToday
        ];

        return view('attendance.hr_mark', compact(
            'employees',
            'todayAttendance',
            'attendanceCounts'
        ));
    }

    /**
     * Submit HR Bulk Attendance
     */
    public function hrAttendanceBulk(Request $request)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'hr')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.status' => 'required|in:present,absent,late,half_day,leave',
            'attendances.*.remarks' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['attendances'] as $attendanceData) {
                $status = ucfirst($attendanceData['status']);

                $attendance = Attendance::updateOrCreate(
                    [
                        'employee_id' => $attendanceData['employee_id'],
                        'attendance_date' => today()
                    ],
                    [
                        'status' => $status,
                        'remarks' => $attendanceData['remarks'] ?? null,
                        'check_in' => $attendanceData['status'] == 'present' ? now() : null,
                        'marked_by' => Auth::id()
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'count' => count($validated['attendances'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk attendance error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to mark attendance',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get HR Dashboard Quick Stats
     */
    public function getHrQuickStats()
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'hr')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $totalEmployees = Employee::count();
        $presentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'present')
                      ->orWhere('status', 'Present');
            })
            ->count();

        $absentToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'absent')
                      ->orWhere('status', 'Absent');
            })
            ->count();

        $lateToday = Attendance::whereDate('attendance_date', today())
            ->where(function($query) {
                $query->where('status', 'late')
                      ->orWhere('status', 'Late');
            })
            ->count();

        $halfDayToday = Attendance::whereDate('attendance_date', today())
            ->where('status', 'Half Day')
            ->count();

        $markedCount = $presentToday + $absentToday + $lateToday + $halfDayToday;
        $notMarkedToday = max(0, $totalEmployees - $markedCount);

        $pendingLeaves = Leave::where('status', 'pending')->count();
        $onLeaveToday = $this->getOnLeaveTodaySimple();

        return response()->json([
            'total_employees' => $totalEmployees,
            'present_today' => $presentToday,
            'absent_today' => $absentToday,
            'late_today' => $lateToday,
            'half_day_today' => $halfDayToday,
            'not_marked_today' => $notMarkedToday,
            'pending_leaves' => $pendingLeaves,
            'on_leave_today' => $onLeaveToday,
            'attendance_percentage' => $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100, 1) : 0,
            'updated_at' => now()->format('h:i A')
        ]);
    }
}
