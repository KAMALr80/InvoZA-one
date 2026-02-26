<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Models\Purchase;
use App\Models\Product;

/* ================= CONTROLLERS ================= */
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

// Employee & HR
use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\Admin\StaffApprovalController;

// Inventory
use App\Http\Controllers\Inventory\InventoryController;

// Sales & Purchases
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Reports\ReportController;

// Payments & Wallet
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Payments\EmiPaymentController;
use App\Http\Controllers\CustomerWalletController;

// Auth
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OtpController;

// AI
use App\Http\Controllers\AiController;
use App\Http\Controllers\AiAssistantController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect('/login'));

// Test Mail Route
Route::get('/test-mail', function () {
    Mail::raw('Hello 👋 Mailtrap test successful!', function ($message) {
        $message->to('test@demo.com')->subject('Mailtrap Test');
    });
    return 'Mail sent successfully!';
});

// Public Auth Routes
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::get('/otp-verify', [OtpController::class, 'show'])->name('otp.verify');
Route::post('/otp-verify', [OtpController::class, 'verify'])->name('otp.verify.post');
Route::post('/otp-resend', [OtpController::class, 'resend'])->name('otp.resend');

// Public Customer AJAX Routes
Route::get('/customers/ajax-search', [CustomerController::class, 'ajaxSearch'])->name('customers.ajax.search');
Route::post('/customers/store-ajax', [CustomerController::class, 'storeAjax'])->name('customers.store.ajax');

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* ================= PROFILE ================= */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ================= ATTENDANCE ================= */
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', function () {
            if (Auth::user()->role === 'staff') {
                return redirect()->route('attendance.my');
            }
            if (in_array(Auth::user()->role, ['admin', 'hr'])) {
                return redirect()->route('attendance.manage');
            }
            abort(403);
        });

        Route::get('/my', [AttendanceController::class, 'myAttendance'])->name('my');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('checkin');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('checkout');

        Route::middleware('hr')->group(function () {
            Route::get('/manage', [AttendanceController::class, 'manage'])->name('manage');
            Route::get('/mark', [AttendanceController::class, 'markAttendance'])->name('mark');
            Route::post('/bulk', [AttendanceController::class, 'bulkAttendance'])->name('bulk');
            Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');
            Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('update');
        });
    });

    /* ================= LEAVES ================= */
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/my', [LeaveController::class, 'myLeaves'])->name('my');
        Route::post('/apply', [LeaveController::class, 'apply'])->name('apply');

        Route::middleware('hr')->group(function () {
            Route::get('/manage', [LeaveController::class, 'manage'])->name('manage');
            Route::post('/{leave}/approve', [LeaveController::class, 'approve'])->name('approve');
            Route::post('/{leave}/reject', [LeaveController::class, 'reject'])->name('reject');
        });
    });

    /* ================= EMPLOYEES (Admin Only) ================= */
    Route::middleware('admin')->prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
        Route::get('/search', [EmployeeController::class, 'search'])->name('search');
        Route::post('/{employee}/send-email', [EmployeeController::class, 'sendEmail'])->name('send.email');
    });

    /* ================= INVENTORY (Admin Only) ================= */
    Route::middleware('admin')->prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/create', [InventoryController::class, 'create'])->name('create');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
        Route::get('/{inventory}', [InventoryController::class, 'show'])->name('show');
        Route::get('/{inventory}/edit', [InventoryController::class, 'edit'])->name('edit');
        Route::put('/{inventory}', [InventoryController::class, 'update'])->name('update');
        Route::delete('/{inventory}', [InventoryController::class, 'destroy'])->name('destroy');
        Route::get('/search', [InventoryController::class, 'ajaxSearch'])->name('ajax.search');
        Route::post('/{id}/update-quantity', [InventoryController::class, 'updateQuantity'])->name('update.quantity');
        Route::post('/bulk-delete', [InventoryController::class, 'bulkDelete'])->name('bulk.delete');
        Route::post('/barcode-preview', [InventoryController::class, 'barcodePreview'])->name('barcode.preview');
        Route::post('/barcode-download', [InventoryController::class, 'barcodeDownload'])->name('barcode.download');
    });

    /* ================= STAFF APPROVAL (Admin Only) ================= */
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/staff-approval', [StaffApprovalController::class, 'index'])->name('staff.approval');
        Route::post('/staff-approval/{id}', [StaffApprovalController::class, 'approve'])->name('staff.approve');
    });

    /* ================= CUSTOMERS ================= */
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::get('/{customer}/sales', [CustomerController::class, 'sales'])->name('sales');
        Route::get('/{customer}/payments', [CustomerController::class, 'payments'])->name('payments');
        Route::get('/{customer}/wallet', [CustomerWalletController::class, 'customerReport'])->name('wallet');
    });

    /* ================= WALLET (Independent) ================= */
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::post('/add', [CustomerWalletController::class, 'addAdvance'])->name('add');
        Route::post('/use', [CustomerWalletController::class, 'useAdvance'])->name('use');
        Route::delete('/{wallet}', [CustomerWalletController::class, 'destroy'])->name('delete');
        Route::get('/delete-impact/{wallet}', [CustomerWalletController::class, 'deleteImpact'])->name('delete.impact');
        Route::get('/history/{customer}', [CustomerWalletController::class, 'getHistory'])->name('history');
        Route::get('/report', [CustomerWalletController::class, 'report'])->name('report');
        Route::post('/recalculate/{customer}', [CustomerWalletController::class, 'recalculate'])->name('recalculate');
    });

    /* ================= SALES ================= */
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/create', [SalesController::class, 'create'])->name('create');
        Route::post('/', [SalesController::class, 'store'])->name('store');
        Route::get('/datatable', [SalesController::class, 'datatable'])->name('datatable');
        Route::get('/stats', [SalesController::class, 'stats'])->name('stats');
        Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
        Route::get('/{sale}/invoice', [SalesController::class, 'invoice'])->name('invoice');
        Route::get('/{sale}/print', [SalesController::class, 'print'])->name('print'); // ✅ Added missing print route
        Route::get('/{sale}/edit', [SalesController::class, 'edit'])->name('edit');
        Route::put('/{sale}', [SalesController::class, 'update'])->name('update');
        Route::delete('/{sale}', [SalesController::class, 'destroy'])->name('destroy');
        Route::post('/{sale}/mark-due', [PaymentController::class, 'markAsDue'])->name('mark-due');

        // ✅ Delete with payments route
        Route::delete('/{saleId}/delete-with-payments', [SalesController::class, 'deleteWithPayments'])->name('delete-with-payments');

        // ✅ Delete impact analysis
        Route::get('/{id}/delete-impact', [SalesController::class, 'deleteImpact'])->name('delete-impact');
    });

    /* ================= PURCHASES ================= */
    Route::resource('purchases', PurchaseController::class);

    /* ================= PAYMENTS ================= */
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/create/{sale}', [PaymentController::class, 'create'])->name('create');
        Route::post('/store', [PaymentController::class, 'store'])->name('store');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::delete('/bulk/{saleId}', [PaymentController::class, 'deleteBulk'])->name('delete-bulk'); // ✅ Fixed duplicate
        Route::delete('/customer/{customerId}/delete-all', [PaymentController::class, 'destroyAll'])->name('delete-all'); // ✅ Fixed name
    });

    /* ================= EMI ================= */
    Route::prefix('emi')->name('emi.')->group(function () {
        Route::get('/{emi}', [EmiPaymentController::class, 'show'])->name('show');
        Route::post('/{emi}/pay', [EmiPaymentController::class, 'pay'])->name('pay');
    });

    /* ================= REPORTS ================= */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/sales/excel', [ReportController::class, 'exportSalesCSV'])->name('sales.excel');
        Route::get('/sales/pdf', [ReportController::class, 'exportSalesPDF'])->name('sales.pdf');
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
        Route::get('/purchases/excel', [ReportController::class, 'exportPurchasesCSV'])->name('purchases.excel');
        Route::get('/purchases/pdf', [ReportController::class, 'exportPurchasesPDF'])->name('purchases.pdf');
        Route::get('/attendance', [ReportController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/excel', [ReportController::class, 'exportAttendanceCSV'])->name('attendance.excel');
        Route::get('/attendance/pdf', [ReportController::class, 'exportAttendancePDF'])->name('attendance.pdf');
    });

    /* ================= AI ================= */
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/sales-prediction', [AiController::class, 'salesPrediction'])->name('sales.prediction');
        Route::post('/ask', [AiAssistantController::class, 'ask'])->name('ask');
    });

    /* ================= HR DASHBOARD ================= */
    Route::middleware('hr')->prefix('hr')->name('hr.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'hrDashboard'])->name('dashboard');
        Route::get('/analytics', [DashboardController::class, 'getHrAnalytics'])->name('analytics');
        Route::get('/department-stats', [DashboardController::class, 'getDepartmentStats'])->name('department.stats');
        Route::get('/monthly-attendance', [DashboardController::class, 'getMonthlyAttendance'])->name('monthly.attendance');
    });
});

/* ================= ADDITIONAL ROUTES (Outside Auth Group) ================= */
// Ye routes already auth group ke andar hain, isliye yahan duplicate nahi hone chahiye
// Aapne jo neeche likha hai wo duplicate hai, isliye comment out kiya


Route::delete('/invoices/{id}/delete', [SalesController::class, 'destroy'])->name('invoices.delete');
Route::delete('/customers/{customerId}/payments/delete-all', [PaymentController::class, 'destroyAll'])->name('payments.delete-all');
Route::delete('/payments/bulk/{saleId}', [PaymentController::class, 'deleteBulk'])->name('payments.delete-bulk');
Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
Route::delete('/payments/bulk/{saleId}', [PaymentController::class, 'deleteBulk'])->name('payments.delete-bulk');
Route::get('/customers/{customer}/payments', [CustomerController::class, 'payments'])->name('customers.payments');
Route::delete('/customers/{customerId}/payments/delete-all', [PaymentController::class, 'destroyAll'])->name('payments.delete-all');
Route::delete('/invoices/{saleId}/delete-with-payments', [SalesController::class, 'deleteWithPayments'])->name('invoices.delete-with-payments');


    // Route::delete('/{saleId}/delete-with-payments', [SalesController::class, 'deleteWithPayments'])
    //     ->name('sales.delete-with-payments');


    Route::get('/customers/{id}/details', [CustomerController::class, 'getDetails'])->name('customers.details');



    
Route::get('/test-relationship', function() {
    try {
        // Test 1: Check if Product model exists
        $productCount = Product::count();
        
        // Test 2: Check if Purchase model exists
        $purchaseCount = Purchase::count();
        
        // Test 3: Try to get a purchase with product
        $purchase = Purchase::with('product')->first();
        
        $results = [
            'product_model_exists' => 'Yes',
            'total_products' => $productCount,
            'purchase_model_exists' => 'Yes',
            'total_purchases' => $purchaseCount,
        ];
        
        if ($purchase) {
            $results['sample_purchase'] = [
                'id' => $purchase->id,
                'invoice' => $purchase->invoice_number,
                'product_loaded' => $purchase->product ? 'Yes' : 'No',
                'product_name' => $purchase->product ? $purchase->product->name : 'No product found',
                'product_id' => $purchase->product_id
            ];
        } else {
            $results['message'] = 'No purchases found. Create one first.';
        }
        
        return response()->json($results);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
});