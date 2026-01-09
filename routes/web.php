<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Admin\StaffApprovalController;
use App\Http\Controllers\LeaveController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/dashboard'));

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* ===== DASHBOARD ===== */
    Route::get('/dashboard', [DashboardController::class,'index'])
        ->name('dashboard');

    /* ===== PROFILE ===== */
    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');

    /* ===== ATTENDANCE ===== */
    Route::prefix('attendance')->group(function () {

        Route::get('/', fn () => redirect()->route('attendance.my'));

        Route::get('/my', [AttendanceController::class,'myAttendance'])
            ->name('attendance.my');

        Route::post('/check-in', [AttendanceController::class,'checkIn'])
            ->name('attendance.checkin');

        Route::post('/check-out', [AttendanceController::class,'checkOut'])
            ->name('attendance.checkout');

        Route::middleware('hr')->group(function () {
            Route::get('/manage', [AttendanceController::class,'manage'])
                ->name('attendance.manage');
        });
    });

    /* ===== LEAVES ===== */
    Route::prefix('leaves')->group(function () {

        Route::get('/my', [LeaveController::class,'myLeaves'])
            ->name('leaves.my');

        Route::post('/apply', [LeaveController::class,'apply'])
            ->name('leaves.apply');

        Route::middleware('hr')->group(function () {
            Route::get('/manage', [LeaveController::class,'manage'])
                ->name('leaves.manage');

            Route::post('/{id}/approve', [LeaveController::class,'approve'])
                ->name('leaves.approve');

            Route::post('/{id}/reject', [LeaveController::class,'reject'])
                ->name('leaves.reject');
        });
    });

    /* ===== ADMIN ===== */
    Route::middleware('admin')->group(function () {

        Route::resource('employees', EmployeeController::class);
        Route::resource('inventory', InventoryController::class);

        Route::get('/admin/staff-approval',
            [StaffApprovalController::class,'index']
        )->name('admin.staff.approval');

        Route::post('/admin/staff-approval/{id}',
            [StaffApprovalController::class,'approve']
        )->name('admin.staff.approve');
    });

    /* ===== SALES ===== */
    Route::resource('sales', SalesController::class);

    /* ===== PURCHASES ===== */
    Route::resource('purchases', PurchaseController::class);

    /* ===== REPORTS ===== */
    Route::prefix('reports')->group(function () {

        Route::get('/sales', [ReportController::class,'sales'])
            ->name('reports.sales');

        Route::get('/sales/excel', [ReportController::class,'exportSalesCSV'])
            ->name('reports.sales.excel');

        Route::get('/sales/pdf', [ReportController::class,'exportSalesPDF'])
            ->name('reports.sales.pdf');

        Route::get('/purchases', [ReportController::class,'purchases'])
            ->name('reports.purchases');

        Route::get('/purchases/excel', [ReportController::class,'exportPurchasesCSV'])
            ->name('reports.purchases.excel');

        Route::get('/purchases/pdf', [ReportController::class,'exportPurchasesPDF'])
            ->name('reports.purchases.pdf');

        Route::get('/attendance', [ReportController::class,'attendance'])
            ->name('reports.attendance');

        Route::get('/attendance/excel', [ReportController::class,'exportAttendanceCSV'])
            ->name('reports.attendance.excel');

        Route::get('/attendance/pdf', [ReportController::class,'exportAttendancePDF'])
            ->name('reports.attendance.pdf');
    });

});




