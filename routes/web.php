<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
use App\Http\Controllers\Payments\EmiPaymentController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AiAssistantController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));

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

        // HR ATTENDANCE MANAGEMENT
        Route::middleware('hr')->group(function () {
            Route::get('/manage', [AttendanceController::class, 'manage'])
                ->name('attendance.manage');

            Route::get('/mark', [AttendanceController::class, 'markAttendance'])
                ->name('attendance.mark');

            Route::post('/bulk', [AttendanceController::class, 'bulkAttendance'])
                ->name('attendance.bulk');

            Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])
                ->name('attendance.edit');

            Route::put('/{attendance}', [AttendanceController::class, 'update'])
                ->name('attendance.update');
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

            Route::post('/{leave}/approve', [LeaveController::class, 'approve'])
                ->name('leaves.approve');

            Route::post('/{leave}/reject', [LeaveController::class, 'reject'])
                ->name('leaves.reject');
        });
    });

    /* ================= ADMIN ================= */
    Route::middleware('admin')->group(function () {

        // EMPLOYEES
        Route::resource('employees', EmployeeController::class);
        Route::get('/employees/search', [EmployeeController::class, 'search'])
            ->name('employees.search');

        // INVENTORY
        Route::resource('inventory', InventoryController::class);
        Route::get('/inventory/search', [InventoryController::class, 'ajaxSearch'])
            ->name('inventory.ajax.search');
        Route::post('/inventory/{id}/update-quantity', [InventoryController::class, 'updateQuantity'])
            ->name('inventory.update.quantity');
        Route::post('/inventory/bulk-delete', [InventoryController::class, 'bulkDelete'])
            ->name('inventory.bulk.delete');
        Route::post('/inventory/barcode-preview', [InventoryController::class, 'barcodePreview'])
            ->name('inventory.barcode.preview');

        // STAFF APPROVAL
        Route::get('/admin/staff-approval', [StaffApprovalController::class, 'index'])
            ->name('admin.staff.approval');

        Route::post('/admin/staff-approval/{id}', [StaffApprovalController::class, 'approve'])
            ->name('admin.staff.approve');
    });

      /* ================= SALES ================= */
   /* ================= SALES ================= */
Route::middleware('auth')->prefix('sales')->group(function () {

    Route::get('/', [SalesController::class, 'index'])
        ->name('sales.index');

    Route::get('/create', [SalesController::class, 'create'])
        ->name('sales.create');

    Route::post('/', [SalesController::class, 'store'])
        ->name('sales.store');

    // ✅ DATATABLE (AJAX)
    Route::get('/datatable', [SalesController::class, 'datatable'])
        ->name('sales.datatable');

    // ✅ STATS (AJAX)
    Route::get('/stats', [SalesController::class, 'stats'])
        ->name('sales.stats');

    Route::get('/{sale}', [SalesController::class, 'show'])
        ->name('sales.show');

    Route::get('/{sale}/invoice', [SalesController::class, 'invoice'])
        ->name('sales.invoice');

    Route::get('/{sale}/edit', [SalesController::class, 'edit'])
        ->name('sales.edit');

    Route::put('/{sale}', [SalesController::class, 'update'])
        ->name('sales.update');

    Route::get('/{sale}/view', [SalesController::class, 'view'])
        ->name('sales.view');


        Route::delete('/{sale}', [SalesController::class, 'destroy'])
        ->name('sales.destroy');

        Route::post('/{sale}/mark-due', [PaymentController::class, 'markAsDue'])->name('sales.mark-due');
});


    /* ================= PURCHASES ================= */
    Route::resource('purchases', PurchaseController::class);

    /* ================= CUSTOMERS ================= */
    Route::prefix('customers')->name('customers.')->group(function () {
        // AJAX ROUTES
        Route::get('ajax-search', [CustomerController::class, 'ajaxSearch'])
            ->name('ajax.search');

        Route::post('ajax-store', [CustomerController::class, 'ajaxStore'])
            ->name('ajax.store');

        // SALES HISTORY
        Route::get('{customer}/sales', [CustomerController::class, 'sales'])
            ->name('sales');

        // CRUD ROUTES
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
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

    /* ================= PAYMENTS ================= */
    Route::prefix('payments')->group(function () {
        Route::get('/create/{sale}', [PaymentController::class, 'create'])
            ->name('payments.create');
        Route::post('/store', [PaymentController::class, 'store'])
            ->name('payments.store');
    });

    /* ================= EMI PAYMENTS ================= */
    Route::prefix('emi')->group(function () {
        Route::get('/{emi}', [EmiPaymentController::class, 'show'])
            ->name('emi.show');
        Route::post('/{emi}/pay', [EmiPaymentController::class, 'pay'])
            ->name('emi.pay');
    });

    /* ================= AI FEATURES ================= */
    Route::prefix('ai')->group(function () {
        Route::get('/sales-prediction', [AiController::class, 'salesPrediction'])
            ->name('ai.sales.prediction');
        Route::post('/ask', [AiAssistantController::class, 'ask'])
            ->name('ai.ask');
    });

    /* ================= HR DASHBOARD ================= */
    Route::middleware('hr')->prefix('hr')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'hrDashboard'])
            ->name('hr.dashboard');
        Route::get('/analytics', [DashboardController::class, 'getHrAnalytics'])
            ->name('hr.analytics');
        Route::get('/department-stats', [DashboardController::class, 'getDepartmentStats'])
            ->name('hr.department.stats');
        Route::get('/monthly-attendance', [DashboardController::class, 'getMonthlyAttendance'])
            ->name('hr.monthly.attendance');
    });
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

Route::get('/otp-verify', [OtpController::class, 'show'])
    ->name('otp.verify');

Route::post('/otp-verify', [OtpController::class, 'verify'])
    ->name('otp.verify.post');

Route::post('/otp-resend', [OtpController::class, 'resend'])
    ->name('otp.resend');

/*
|--------------------------------------------------------------------------
| CUSTOMER AJAX ROUTES (PUBLIC FOR AJAX CALLS)
|--------------------------------------------------------------------------
*/
Route::get('/customers/ajax-search', [CustomerController::class, 'ajaxSearch'])
    ->name('customers.ajax.search');

Route::post('/customers/store-ajax', [CustomerController::class, 'storeAjax'])
    ->name('customers.store.ajax');


use Illuminate\Support\Facades\Mail;

Route::get('/test-mail', function () {
    Mail::raw('Hello Kamalsinh 👋 Mailtrap bilkul sahi kaam kar raha hai!', function ($message) {
        $message->to('test@demo.com')
                ->subject('Mailtrap Sandbox Test');
    });

    return 'Mail sent successfully!';
});



// Employee routes
Route::prefix('employees')->middleware('auth')->group(function () {
    Route::get('/', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // ✅ Email send route (IMPORTANT: yahi route aapke blade mein use ho raha hai)
    Route::post('/{employee}/send-email', [EmployeeController::class, 'sendEmail'])
        ->name('employee.send.email');
});


// web.php में
Route::prefix('inventory')->group(function () {
    // ... other routes ...

    // Barcode preview page (GET)
    Route::post('/barcode-preview', [InventoryController::class, 'barcodePreview'])
        ->name('inventory.barcode.preview');

    // Barcode PDF download (POST)
    Route::post('/barcode-download', [InventoryController::class, 'barcodeDownload'])
        ->name('inventory.barcode.download');
});
