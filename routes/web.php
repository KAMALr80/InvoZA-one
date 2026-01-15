<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/* ================= CONTROLLERS ================= */
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\LeaveController;

use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Customers\CustomerController;

use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Admin\StaffApprovalController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/dashboard'));

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* ================= DASHBOARD ================= */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /* ================= PROFILE ================= */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ================= ATTENDANCE ================= */
    Route::prefix('attendance')->group(function () {

        Route::get('/', function () {
            if (Auth::user()->role === 'staff') {
                return redirect()->route('attendance.my');
            }

            if (in_array(Auth::user()->role, ['admin', 'hr'])) {
                return redirect()->route('attendance.manage');
            }

            abort(403);
        });

        // STAFF
        Route::get('/my', [AttendanceController::class, 'myAttendance'])
            ->name('attendance.my');

        Route::post('/check-in', [AttendanceController::class, 'checkIn'])
            ->name('attendance.checkin');

        Route::post('/check-out', [AttendanceController::class, 'checkOut'])
            ->name('attendance.checkout');

        // ADMIN / HR
        Route::middleware('hr')->group(function () {
            Route::get('/manage', [AttendanceController::class, 'manage'])
                ->name('attendance.manage');
        });
    });

    /* ================= LEAVES ================= */
    Route::prefix('leaves')->group(function () {

        // STAFF
        Route::get('/my', [LeaveController::class, 'myLeaves'])
            ->name('leaves.my');

        Route::post('/apply', [LeaveController::class, 'apply'])
            ->name('leaves.apply');

        // ADMIN / HR
        Route::middleware('hr')->group(function () {
            Route::get('/manage', [LeaveController::class, 'manage'])
                ->name('leaves.manage');

            Route::post('/{id}/approve', [LeaveController::class, 'approve'])
                ->name('leaves.approve');

            Route::post('/{id}/reject', [LeaveController::class, 'reject'])
                ->name('leaves.reject');
        });
    });

    /* ================= ADMIN ================= */
    Route::middleware('admin')->group(function () {

        Route::resource('employees', EmployeeController::class);
        Route::resource('inventory', InventoryController::class);

        Route::get('/admin/staff-approval',
            [StaffApprovalController::class, 'index']
        )->name('admin.staff.approval');

        Route::post('/admin/staff-approval/{id}',
            [StaffApprovalController::class, 'approve']
        )->name('admin.staff.approve');
    });

    /* ================= SALES ================= */
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/create', [SalesController::class, 'create'])->name('create');
        Route::post('/', [SalesController::class, 'store'])->name('store');
        Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
        Route::get('/{sale}/invoice', [SalesController::class, 'invoice'])->name('invoice');
    });

    /* ================= PURCHASES ================= */
    Route::resource('purchases', PurchaseController::class);

    /* ================= CUSTOMERS ================= */
    Route::resource('customers', CustomerController::class);

    /* ================= REPORTS ================= */
    Route::prefix('reports')->group(function () {

        Route::get('/sales', [ReportController::class, 'sales'])
            ->name('reports.sales');

        Route::get('/sales/excel', [ReportController::class, 'exportSalesCSV'])
            ->name('reports.sales.excel');

        Route::get('/sales/pdf', [ReportController::class, 'exportSalesPDF'])
            ->name('reports.sales.pdf');

        Route::get('/purchases', [ReportController::class, 'purchases'])
            ->name('reports.purchases');

        Route::get('/purchases/excel', [ReportController::class, 'exportPurchasesCSV'])
            ->name('reports.purchases.excel');

        Route::get('/purchases/pdf', [ReportController::class, 'exportPurchasesPDF'])
            ->name('reports.purchases.pdf');

        Route::get('/attendance', [ReportController::class, 'attendance'])
            ->name('reports.attendance');

        Route::get('/attendance/excel', [ReportController::class, 'exportAttendanceCSV'])
            ->name('reports.attendance.excel');

        Route::get('/attendance/pdf', [ReportController::class, 'exportAttendancePDF'])
            ->name('reports.attendance.pdf');
    });

});



Route::middleware('auth')->group(function () {
    Route::post(
        '/customers/ajax-store',
        [CustomerController::class, 'ajaxStore']
    )->name('customers.ajax.store');
});


Route::get('/customers/{customer}/sales',
    [CustomerController::class, 'sales']
)->name('customers.sales');



Route::middleware('auth')->group(function () {

    Route::resource('customers', CustomerController::class);

    // 👁 Customer Sales History
    Route::get(
        'customers/{customer}/sales',
        [CustomerController::class, 'sales']
    )->name('customers.sales');

    // AJAX customer add (from sales page)
    Route::post(
        'customers/ajax-store',
        [CustomerController::class, 'ajaxStore']
    )->name('customers.ajax.store');

});


Route::get('/sales/{sale}/edit', [SalesController::class, 'edit'])
    ->name('sales.edit');

Route::put('/sales/{sale}', [SalesController::class, 'update'])
    ->name('sales.update');

    Route::get('/sales/{sale}/view',
    [SalesController::class, 'view']
)->name('sales.view');




Route::get('/customers/{customer}/sales',
    [CustomerController::class, 'sales']
)->name('customers.sales');




Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::post('/customers/ajax-store', [CustomerController::class, 'ajaxStore'])
    ->name('customers.store.ajax');
