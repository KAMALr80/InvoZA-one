<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();

        $presentToday = Attendance::whereDate('attendance_date', today())
            ->where('status', 'Present')
            ->count();

        $absentToday = Attendance::whereDate('attendance_date', today())
            ->where('status', 'Absent')
            ->count();

        // Sales last 7 days
        $salesLabels = [];
        $salesData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesLabels[] = $date->format('d M');

            $salesData[] = Sale::whereDate('sale_date', $date)->sum('total');
        }

        return view('dashboard.index', compact(
            'totalEmployees',
            'presentToday',
            'absentToday',
            'salesLabels',
            'salesData'
        ));
    }
}
