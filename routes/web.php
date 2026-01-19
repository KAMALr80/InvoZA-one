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
        Route::get('/{sale}/edit', [SalesController::class, 'edit'])->name('edit');
        Route::put('/{sale}', [SalesController::class, 'update'])->name('update');
    });

    /* ================= PURCHASES ================= */
    Route::resource('purchases', PurchaseController::class);

    /* ================= CUSTOMERS (🔥 NO CONFLICT VERSION) ================= */
    Route::prefix('customers')->name('customers.')->group(function () {

        // 🔥 AJAX ROUTES FIRST (VERY IMPORTANT)
        Route::get(
            'ajax-search',
            [CustomerController::class, 'ajaxSearch']
        )->name('ajax.search');

        Route::post(
            'ajax-store',
            [CustomerController::class, 'ajaxStore']
        )->name('ajax.store');

        // 👁 Customer Sales History
        Route::get(
            '{customer}/sales',
            [CustomerController::class, 'sales']
        )->name('sales');

        // CRUD WITHOUT show()
        Route::resource(
            '/',
            CustomerController::class
        )->except(['show'])
         ->parameters(['' => 'customer']);
    });

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


Route::post('/customers/store-ajax', [CustomerController::class, 'storeAjax'])
    ->name('customers.store.ajax');




Route::get('/sales/{sale}', [SalesController::class, 'view'])
    ->name('sales.view');



Route::resource('sales', SalesController::class);




Route::post('/customers/store-ajax', [CustomerController::class, 'storeAjax'])
    ->name('customers.store.ajax');

    Route::get('/sales/{sale}/view', [SalesController::class, 'view'])
    ->name('sales.view');


    use App\Http\Controllers\AiController;

Route::get('/ai/sales-prediction', [AiController::class, 'salesPrediction'])
    ->middleware('auth');


    use App\Http\Controllers\AiAssistantController;

Route::post('/ai/ask', [AiAssistantController::class, 'ask'])
    ->middleware('auth');




use App\Http\Controllers\Payments\PaymentController;

Route::get('/payments/create/{sale}', [PaymentController::class, 'create'])
    ->name('payments.create');

Route::post('/payments/store', [PaymentController::class, 'store'])
    ->name('payments.store');





/* AJAX CUSTOMER SEARCH */
Route::get('/customers/ajax-search', [CustomerController::class, 'ajaxSearch'])
    ->name('customers.ajax.search');

/* AJAX CUSTOMER STORE (MODAL) */
Route::post('/customers/store-ajax', [CustomerController::class, 'storeAjax'])
    ->name('customers.store.ajax');


  use App\Http\Controllers\Products\ProductController;

Route::resource('products', inventoryController::class);
