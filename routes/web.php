<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
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
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisterOtpController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PasswordController;

// AI
use App\Http\Controllers\AiController;
use App\Http\Controllers\AiAssistantController;

// Logistics
use App\Http\Controllers\Logistics\LogisticsController;
use App\Http\Controllers\Logistics\MapController;
use App\Http\Controllers\Logistics\ServiceAreaController;
use App\Http\Controllers\Logistics\RouteController;
use App\Http\Controllers\Logistics\UpdateShipmentController;
use App\Http\Controllers\Logistics\AgentController;

// API Controllers
use App\Http\Controllers\Api\AgentApiController;
use App\Http\Controllers\Api\TrackingController;
use App\Http\Controllers\Api\ShipmentApiController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\GeocodingController;
use App\Http\Controllers\Api\RouteOptimizationController;

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

// Brevo Test Mail
Route::get('/test-mail-brevo', function () {
    $apiKey = config('app.brevo_key');
    $response = Http::withHeaders([
        'accept' => 'application/json',
        'api-key' => $apiKey,
        'content-type' => 'application/json'
    ])->post('https://api.brevo.com/v3/smtp/email', [
        "sender" => [
            "name" => "INVOZA",
            "email" => "221240116017.it@gmail.com"
        ],
        "to" => [
            ["email" => "221240116017.it@gmail.com"]
        ],
        "subject" => "Test Mail",
        "htmlContent" => "<h1>OTP: 123456</h1>"
    ]);
    return $response->body();
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Public)
|--------------------------------------------------------------------------
*/
// Login Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');

// Registration Routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.post');

// Registration OTP Routes
Route::get('/register-otp', [RegisterOtpController::class, 'show'])->name('register.otp.show');
Route::post('/register-otp/verify', [RegisterOtpController::class, 'verify'])->name('register.otp.verify');
Route::post('/register-otp/resend', [RegisterOtpController::class, 'resend'])->name('register.otp.resend');

// Login OTP Routes
Route::get('/otp-verify', [OtpController::class, 'show'])->name('otp.verify');
Route::post('/otp-verify', [OtpController::class, 'verify'])->name('otp.verify.post');
Route::post('/otp-resend', [OtpController::class, 'resend'])->name('otp.resend');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');

// Public Customer AJAX Routes
Route::get('/customers/ajax-search', [CustomerController::class, 'ajaxSearch'])->name('customers.ajax.search');
Route::post('/customers/store-ajax', [CustomerController::class, 'storeAjax'])->name('customers.store.ajax');

// Public Tracking Route
Route::get('/track/{trackingNumber}', [MapController::class, 'trackShipment'])->name('public.track');

// Test Relationship Route
Route::get('/test-relationship', function() {
    try {
        $productCount = Product::count();
        $purchaseCount = Purchase::count();
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

// Email Routes
Route::post('/sales/send-invoice', [SalesController::class, 'sendInvoice'])->name('sales.send-invoice');
Route::post('/sales/bulk-send-invoice', [SalesController::class, 'bulkSendInvoice'])->name('sales.bulk-send-invoice');
Route::post('/sales/send-due-reminder', [SalesController::class, 'sendDueReminder'])->name('sales.send-due-reminder');
Route::post('/sales/bulk-send-due-reminders', [SalesController::class, 'bulkSendDueReminders'])->name('sales.bulk-send-due-reminders');

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

    // Email Verification Routes
    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')->name('verification.send');

    // Password Confirmation Routes
    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])->name('password.confirm.post');

    // Password Update Route
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // Logout Route
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /* ================= ATTENDANCE ROUTES ================= */
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
    });

    /* ================= LEAVE ROUTES ================= */
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::get('/my', [LeaveController::class, 'myLeaves'])->name('my');
        Route::get('/create', [LeaveController::class, 'create'])->name('create');
        Route::post('/store', [LeaveController::class, 'store'])->name('store');
        Route::get('/{leave}', [LeaveController::class, 'show'])->name('show')->where('leave', '[0-9]+');
        Route::post('/{leave}/cancel', [LeaveController::class, 'cancel'])->name('cancel')->where('leave', '[0-9]+');
        Route::get('/{leave}/print', [LeaveController::class, 'printLeave'])->name('print')->where('leave', '[0-9]+');
        Route::get('/{leave}/pdf', [LeaveController::class, 'pdf'])->name('pdf')->where('leave', '[0-9]+');
        Route::get('/{leave}/download', [LeaveController::class, 'download'])->name('download')->where('leave', '[0-9]+');
    });

    // Staff leave routes (additional)
    Route::get('/leaves/my', [LeaveController::class, 'myLeaves'])->name('leaves.my');
    Route::post('/leaves/apply', [LeaveController::class, 'apply'])->name('leaves.apply');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{id}', [LeaveController::class, 'show'])->name('leaves.show');
    Route::post('/leaves/{id}/cancel', [LeaveController::class, 'cancel'])->name('leaves.cancel');

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
        Route::get('/{id}/details', [CustomerController::class, 'getDetails'])->name('details');
    });

    /* ================= WALLET ================= */
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
        Route::get('/{sale}/print', [SalesController::class, 'print'])->name('print');
        Route::get('/{sale}/edit', [SalesController::class, 'edit'])->name('edit');
        Route::put('/{sale}', [SalesController::class, 'update'])->name('update');
        Route::delete('/{sale}', [SalesController::class, 'destroy'])->name('destroy');
        Route::post('/{sale}/mark-due', [PaymentController::class, 'markAsDue'])->name('mark-due');
        Route::delete('/{saleId}/delete-with-payments', [SalesController::class, 'deleteWithPayments'])->name('delete-with-payments');
        Route::get('/{id}/delete-impact', [SalesController::class, 'deleteImpact'])->name('delete-impact');
        Route::get('/{sale}/create-shipment', [SalesController::class, 'createShipment'])->name('sales.create-shipment');
    });

    /* ================= PURCHASES ================= */
    Route::resource('purchases', PurchaseController::class);

    /* ================= PAYMENTS ================= */
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/create/{sale}', [PaymentController::class, 'create'])->name('create');
        Route::post('/store', [PaymentController::class, 'store'])->name('store');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::delete('/bulk/{saleId}', [PaymentController::class, 'deleteBulk'])->name('delete-bulk');
        Route::delete('/customer/{customerId}/delete-all', [PaymentController::class, 'destroyAll'])->name('delete-all');
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

    /* ================= EMPLOYEES (Admin/HR Only) ================= */
    Route::middleware('hr')->prefix('employees')->name('employees.')->group(function () {
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

    /* ================= HR DASHBOARD ================= */
    Route::middleware('hr')->prefix('hr')->name('hr.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'hrDashboard'])->name('dashboard');
        Route::get('/analytics', [DashboardController::class, 'getHrAnalytics'])->name('analytics');
        Route::get('/department-stats', [DashboardController::class, 'getDepartmentStats'])->name('department.stats');
        Route::get('/monthly-attendance', [DashboardController::class, 'getMonthlyAttendance'])->name('monthly.attendance');
    });

    /* ================= LEAVE MANAGEMENT (Admin/HR Only) ================= */
    Route::middleware('hr')->prefix('admin/leaves')->name('leaves.')->group(function () {
        Route::get('/manage', [LeaveController::class, 'manage'])->name('manage');
        Route::get('/{leave}', [LeaveController::class, 'adminShow'])->name('admin-show')->where('leave', '[0-9]+');
        Route::post('/{leave}/approve', [LeaveController::class, 'approve'])->name('approve')->where('leave', '[0-9]+');
        Route::post('/{leave}/reject', [LeaveController::class, 'reject'])->name('reject')->where('leave', '[0-9]+');
        Route::post('/bulk-approve', [LeaveController::class, 'bulkApprove'])->name('bulk.approve');
        Route::post('/bulk-reject', [LeaveController::class, 'bulkReject'])->name('bulk.reject');
        Route::get('/calendar-data', [LeaveController::class, 'calendarData'])->name('calendar.data');
        Route::get('/export', [LeaveController::class, 'export'])->name('export');
        Route::get('/balance/{employeeId?}', [LeaveController::class, 'getBalance'])->name('balance');
    });

    // Admin leave routes (additional)
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/leaves/dashboard', [LeaveController::class, 'dashboard'])->name('leaves.dashboard');
        Route::get('/admin/leaves/manage', [LeaveController::class, 'manage'])->name('leaves.manage');
        Route::get('/admin/leaves/{id}', [LeaveController::class, 'adminShow'])->name('leaves.admin-show');
        Route::post('/admin/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('/admin/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    });

    /* ================= ATTENDANCE MANAGEMENT (Admin/HR Only) ================= */
    Route::middleware('hr')->prefix('admin/attendance')->name('attendance.')->group(function () {
        Route::get('/manage', [AttendanceController::class, 'manage'])->name('manage');
        Route::get('/mark', [AttendanceController::class, 'markAttendance'])->name('mark');
        Route::post('/bulk', [AttendanceController::class, 'bulkAttendance'])->name('bulk');
        Route::get('/report', [AttendanceController::class, 'report'])->name('report');
        Route::get('/edit/{id}', [AttendanceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AttendanceController::class, 'update'])->name('update');
        Route::delete('/{id}', [AttendanceController::class, 'destroy'])->name('destroy');
        Route::get('/export', [AttendanceController::class, 'export'])->name('export');
    });

    /* ================= STAFF APPROVAL (Admin Only) ================= */
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/staff-approval', [StaffApprovalController::class, 'index'])->name('staff.approval');
        Route::post('/staff-approval/{id}', [StaffApprovalController::class, 'approve'])->name('staff.approve');
    });

    /* ================= LOGISTICS & SHIPPING ROUTES ================= */
    Route::prefix('logistics')->name('logistics.')->group(function () {

        // SHIPMENTS
        Route::get('/shipments', [LogisticsController::class, 'index'])->name('shipments.index');
        Route::get('/shipments/create', [LogisticsController::class, 'create'])->name('shipments.create');
        Route::post('/shipments', [LogisticsController::class, 'store'])->name('shipments.store');
        Route::get('/shipments/{id}', [LogisticsController::class, 'show'])->name('shipments.show');
        Route::get('/shipments/{id}/edit', [UpdateShipmentController::class, 'edit'])->name('shipments.edit');
        Route::put('/shipments/{id}', [UpdateShipmentController::class, 'update'])->name('shipments.update');
        Route::post('/shipments/bulk/create', [LogisticsController::class, 'bulkCreate'])->name('shipments.bulk.create');

        // Shipment Actions
        Route::post('/shipments/{id}/status', [LogisticsController::class, 'updateStatus'])->name('shipments.status');
        Route::post('/shipments/{id}/assign-agent', [LogisticsController::class, 'assignAgent'])->name('shipments.assign-agent');
        Route::post('/shipments/{id}/upload-pod', [LogisticsController::class, 'uploadPOD'])->name('shipments.upload-pod');
        Route::get('/shipments/{id}/track', [LogisticsController::class, 'track'])->name('shipments.track');

        // Tracking
        Route::get('/track/{trackingNumber}', [LogisticsController::class, 'track'])->name('track');

        // API Routes for Delivery Boy
        Route::post('/api/shipments/{id}/location', [LogisticsController::class, 'updateLocation'])->name('api.update-location');

        // DELIVERY AGENTS
        Route::get('/agents', [LogisticsController::class, 'agents'])->name('agents.index');
        Route::get('/agents/create', [AgentController::class, 'create'])->name('agents.create');
        Route::post('/agents', [AgentController::class, 'store'])->name('agents.store');
        Route::get('/agents/{id}', [AgentController::class, 'show'])->name('agents.show');
        Route::get('/agents/{id}/edit', [AgentController::class, 'edit'])->name('agents.edit');
        Route::put('/agents/{id}', [AgentController::class, 'update'])->name('agents.update');
        Route::delete('/agents/{id}', [AgentController::class, 'destroy'])->name('agents.destroy');
        Route::post('/agents/{id}/status', [AgentController::class, 'updateStatus'])->name('agents.update-status');
        Route::post('/agents/{id}/upload-documents', [AgentController::class, 'uploadDocuments'])->name('agents.upload-documents');
        Route::get('/agents/map/locations', [LogisticsController::class, 'getAgentsForMap'])->name('agents.map');
        Route::get('/agents/{id}/performance', [AgentController::class, 'performanceReport'])->name('agents.performance');

        // Agent Location API (for tracking)
        Route::get('/agents/{id}/location', [AgentController::class, 'getLocation'])->name('agents.location');

        // REPORTS
        Route::get('/reports', [LogisticsController::class, 'reports'])->name('reports');

        // SERVICE AREAS
        Route::get('/service-areas', [ServiceAreaController::class, 'index'])->name('service-areas');
        Route::get('/service-areas/heatmap', [ServiceAreaController::class, 'heatmapData'])->name('heatmap');

        // ROUTE PLANNER
        Route::get('/route-planner', [RouteController::class, 'index'])->name('route-planner');
        Route::post('/route/calculate', [RouteController::class, 'calculate'])->name('route.calculate');
        Route::post('/route/assign', [RouteController::class, 'assign'])->name('route.assign');

        // MAPS
        Route::get('/map/{trackingNumber}', [MapController::class, 'trackShipment'])->name('map');
    });

    // Additional logistics routes outside group (for specific needs)
    Route::get('/shipments/{id}/track', [LogisticsController::class, 'track'])->name('shipments.track');
});

// Route Planner Routes (outside auth if needed - but keeping inside for consistency)
Route::prefix('logistics')->name('logistics.')->group(function () {
    Route::get('/route-planner', [RouteController::class, 'index'])->name('route-planner');
    Route::post('/route/calculate', [RouteController::class, 'calculate'])->name('route.calculate');
    Route::post('/route/assign', [RouteController::class, 'assign'])->name('route.assign');
});

// Agent API location routes
Route::get('/agents/{id}/location', [AgentController::class, 'getLocation'])->name('agents.location');
Route::get('/api/agents/{id}/location', [AgentController::class, 'getLocation'])->name('agents.api.location');

/*
|--------------------------------------------------------------------------
| API ROUTES (Emergency Load - For Testing)
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {

    // Test route
    Route::get('/test', function () {
        return response()->json([
            'success' => true,
            'message' => 'Logistics API is working',
            'timestamp' => now()->toDateTimeString()
        ]);
    });

    // App login route
    Route::prefix('app')->group(function () {
        Route::post('/login', [AgentApiController::class, 'appLogin']);
    });

    // Tracking
    Route::prefix('track')->group(function () {
        Route::get('/{trackingNumber}', [TrackingController::class, 'track']);
        Route::get('/shipment/{shipmentNumber}', [TrackingController::class, 'trackByShipment']);
        Route::get('/{trackingNumber}/timeline', [TrackingController::class, 'timeline']);
        Route::get('/{trackingNumber}/location', [TrackingController::class, 'currentLocation']);
    });

    // Shipments
    Route::prefix('shipments')->group(function () {
        Route::get('/', [ShipmentApiController::class, 'index']);
        Route::get('/{id}', [ShipmentApiController::class, 'show']);
        Route::post('/', [ShipmentApiController::class, 'store']);
        Route::put('/{id}', [ShipmentApiController::class, 'update']);
        Route::delete('/{id}', [ShipmentApiController::class, 'destroy']);
        Route::post('/{id}/status', [ShipmentApiController::class, 'updateStatus']);
        Route::post('/{id}/assign-agent', [ShipmentApiController::class, 'assignAgent']);
        Route::post('/{id}/live-location', [ShipmentApiController::class, 'updateLiveLocation']);
        Route::post('/{id}/upload-pod', [ShipmentApiController::class, 'uploadPOD']);
        Route::get('/{id}/pod', [ShipmentApiController::class, 'getPOD']);
        Route::post('/{id}/cancel', [ShipmentApiController::class, 'cancel']);
        Route::get('/{id}/tracking', [ShipmentApiController::class, 'trackingHistory']);
    });

    // Agents
    Route::prefix('agents')->group(function () {
        Route::get('/', [AgentApiController::class, 'index']);
        Route::get('/{id}', [AgentApiController::class, 'show']);
        Route::post('/', [AgentApiController::class, 'store']);
        Route::put('/{id}', [AgentApiController::class, 'update']);
        Route::delete('/{id}', [AgentApiController::class, 'destroy']);
        Route::post('/{id}/status', [AgentApiController::class, 'updateStatus']);
        Route::post('/{id}/location', [AgentApiController::class, 'updateLocation']);
        Route::get('/{id}/location', [AgentApiController::class, 'getLocation']);
        Route::get('/{id}/performance', [AgentApiController::class, 'performance']);
        Route::get('/{id}/shipments', [AgentApiController::class, 'assignedShipments']);
        Route::get('/map/all', [AgentApiController::class, 'getAllForMap']);
        Route::get('/nearby', [AgentApiController::class, 'findNearby']);
    });

    // Locations
    Route::prefix('locations')->group(function () {
        Route::get('/search', [LocationController::class, 'search']);
        Route::get('/reverse', [LocationController::class, 'reverse']);
        Route::post('/route', [LocationController::class, 'calculateRoute']);
        Route::post('/distance-matrix', [LocationController::class, 'distanceMatrix']);
        Route::post('/validate', [LocationController::class, 'validateAddress']);
        Route::get('/place/{placeId}', [LocationController::class, 'placeDetails']);
    });

    // Routes
    Route::prefix('routes')->group(function () {
        Route::post('/optimize', [RouteOptimizationController::class, 'optimize']);
        Route::post('/calculate', [RouteOptimizationController::class, 'calculate']);
        Route::post('/distance-matrix', [RouteOptimizationController::class, 'distanceMatrix']);
        Route::post('/assign', [RouteOptimizationController::class, 'assign']);
    });

    // Public
    Route::prefix('public')->group(function () {
        Route::get('/track/{trackingNumber}', [TrackingController::class, 'publicTrack']);
        Route::get('/agent/{id}', [AgentApiController::class, 'publicInfo']);
        Route::get('/timeline/{trackingNumber}', [TrackingController::class, 'publicTimeline']);
    });
});




use App\Http\Controllers\Logistics\ShipmentsController;

/*
|--------------------------------------------------------------------------
| Logistics Routes
|--------------------------------------------------------------------------
*/

Route::prefix('logistics')->name('logistics.')->middleware(['auth'])->group(function () {

    // Shipments Routes
    Route::get('/shipments', [ShipmentsController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [ShipmentsController::class, 'create'])->name('shipments.create');
    Route::post('/shipments', [ShipmentsController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/{id}', [ShipmentsController::class, 'show'])->name('shipments.show');
    Route::get('/shipments/{id}/edit', [ShipmentsController::class, 'edit'])->name('shipments.edit');
    Route::put('/shipments/{id}', [ShipmentsController::class, 'update'])->name('shipments.update');
    Route::delete('/shipments/{id}', [ShipmentsController::class, 'destroy'])->name('shipments.destroy');

    // ✅ ADD THIS MISSING ROUTE - Live Track
    Route::get('/live-track/{trackingNumber}', [LogisticsController::class, 'liveTrack'])->name('live-track');

    // Status Update Routes
    Route::post('/shipments/{id}/status', [ShipmentsController::class, 'updateStatus'])->name('shipments.update-status');
    Route::post('/shipments/{id}/location', [ShipmentsController::class, 'updateLocation'])->name('shipments.update-location');
    Route::post('/shipments/{id}/upload-pod', [LogisticsController::class, 'uploadPOD'])->name('shipments.upload-pod');
    Route::post('/shipments/{id}/assign-agent', [LogisticsController::class, 'assignAgent'])->name('shipments.assign-agent');

    // Tracking Routes
    Route::get('/track/{trackingNumber}', [TrackingController::class, 'track'])->name('track');
    Route::get('/track-web/{trackingNumber}', [LogisticsController::class, 'trackWeb'])->name('track-web');

    // Agent Routes
    Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/create', [AgentController::class, 'create'])->name('agents.create');
    Route::post('/agents', [AgentController::class, 'store'])->name('agents.store');
    Route::get('/agents/{id}', [AgentController::class, 'show'])->name('agents.show');
    Route::get('/agents/{id}/edit', [AgentController::class, 'edit'])->name('agents.edit');
    Route::put('/agents/{id}', [AgentController::class, 'update'])->name('agents.update');
    Route::delete('/agents/{id}', [AgentController::class, 'destroy'])->name('agents.destroy');
    Route::post('/agents/{id}/status', [AgentController::class, 'updateStatus'])->name('agents.update-status');
    Route::post('/agents/{id}/upload-documents', [AgentController::class, 'uploadDocuments'])->name('agents.upload-documents');
    Route::get('/api/agents/{id}/location', [AgentController::class, 'getLocation'])->name('api.agents.location');
    Route::get('/api/agents/map', [AgentController::class, 'getAgentsForMap'])->name('api.agents.map');

    // Route Planning
    Route::get('/route-planner', [RouteController::class, 'index'])->name('route-planner');
    Route::post('/route-calculate', [RouteController::class, 'calculate'])->name('route-calculate');
    Route::post('/route-assign', [RouteController::class, 'assign'])->name('route-assign');

    // Service Areas
    Route::get('/service-areas', [ServiceAreaController::class, 'index'])->name('service-areas');
    Route::get('/service-areas/heatmap', [ServiceAreaController::class, 'heatmapData'])->name('service-areas.heatmap');

    // Reports
    Route::get('/reports', [LogisticsController::class, 'reports'])->name('reports');

    // Bulk Operations
    Route::post('/shipments/bulk-create', [LogisticsController::class, 'bulkCreate'])->name('shipments.bulk-create');
    Route::get('/shipments/bulk/create', [LogisticsController::class, 'bulkCreateForm'])->name('shipments.bulk.create');

    // API Routes (AJAX)
    Route::prefix('api')->group(function () {
        Route::get('/track/{trackingNumber}', [ShipmentsController::class, 'track'])->name('api.track');
        Route::post('/shipments/{id}/location', [ShipmentsController::class, 'updateLocation'])->name('api.shipments.location');
        Route::get('/agents/{id}/location', [AgentController::class, 'getLocation'])->name('api.agents.location');
        Route::get('/agents/map', [AgentController::class, 'getAgentsForMap'])->name('api.agents.map');
        Route::get('/shipments/stats', [ShipmentsController::class, 'stats'])->name('api.shipments.stats');
    });
});

/*
|--------------------------------------------------------------------------
| Public Tracking Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/
Route::get('/track-shipment/{trackingNumber}', [LogisticsController::class, 'trackWeb'])->name('public.track');

// Add this route after other logistics routes
Route::get('logistics/api/available-agents', [App\Http\Controllers\Logistics\LogisticsController::class, 'getAvailableAgents']);


Route::post('/logistics/shipments/{shipment}/remove-agent', [LogisticsController::class, 'removeAgent']);


// ==================== LOGISTICS ROUTES ====================
Route::prefix('logistics')->group(function () {

    // Shipment Management
    Route::get('/shipments', [LogisticsController::class, 'index'])->name('logistics.shipments.index');
    Route::get('/shipments/create', [LogisticsController::class, 'create'])->name('logistics.shipments.create');
    Route::post('/shipments', [LogisticsController::class, 'store'])->name('logistics.shipments.store');
    Route::get('/shipments/{id}', [LogisticsController::class, 'show'])->name('logistics.shipments.show');
    Route::get('/shipments/{id}/edit', [LogisticsController::class, 'edit'])->name('logistics.shipments.edit');
    Route::put('/shipments/{id}', [LogisticsController::class, 'update'])->name('logistics.shipments.update');
    Route::delete('/shipments/{id}', [LogisticsController::class, 'destroy'])->name('logistics.shipments.destroy');

    // Shipment Actions (API endpoints)
    Route::post('/shipments/{id}/status', [LogisticsController::class, 'updateStatus']);
    Route::post('/shipments/{id}/assign-agent', [LogisticsController::class, 'assignAgent']);      // ✅ API for assign agent
    Route::post('/shipments/{id}/remove-agent', [LogisticsController::class, 'removeAgent']);      // ✅ API for remove agent
    Route::post('/shipments/{id}/upload-pod', [LogisticsController::class, 'uploadPOD']);

    // Live Tracking
    Route::get('/live-track/{trackingNumber}', [LogisticsController::class, 'liveTrack'])->name('logistics.live-track');

    // API Routes for AJAX calls
    Route::get('/api/available-agents', [LogisticsController::class, 'getAvailableAgents']);         // ✅ Get agents list
    Route::get('/api/agents/{agentId}/location', [LogisticsController::class, 'getAgentLocation']); // ✅ Get agent location
    Route::post('/api/shipments/{shipment}/location', [LogisticsController::class, 'updateShipmentLocation']);

    // Other Routes
    Route::get('/agents', [LogisticsController::class, 'agents'])->name('logistics.agents');
    Route::get('/reports', [LogisticsController::class, 'reports'])->name('logistics.reports');
    Route::post('/bulk-create', [LogisticsController::class, 'bulkCreate'])->name('logistics.bulk-create');
    Route::get('/agents-map', [LogisticsController::class, 'getAgentsForMap']);
    Route::get('/track/{trackingNumber}', [LogisticsController::class, 'trackWeb']);
});



// ==================== LOGISTICS ROUTES ====================
Route::prefix('logistics')->group(function () {

    // Shipment Management
    Route::get('/shipments', [LogisticsController::class, 'index'])->name('logistics.shipments.index');
    Route::get('/shipments/create', [LogisticsController::class, 'create'])->name('logistics.shipments.create');
    Route::post('/shipments', [LogisticsController::class, 'store'])->name('logistics.shipments.store');
    Route::get('/shipments/{id}', [LogisticsController::class, 'show'])->name('logistics.shipments.show');
    Route::get('/shipments/{id}/edit', [LogisticsController::class, 'edit'])->name('logistics.shipments.edit');
    Route::put('/shipments/{id}', [LogisticsController::class, 'update'])->name('logistics.shipments.update');
    Route::delete('/shipments/{id}', [LogisticsController::class, 'destroy'])->name('logistics.shipments.destroy');

    // ========== ADD THESE ROUTES ==========
    // Shipment Actions (API endpoints)
    Route::post('/shipments/{id}/status', [LogisticsController::class, 'updateStatus']);
    Route::post('/shipments/{id}/assign-agent', [LogisticsController::class, 'assignAgent']);
    Route::post('/shipments/{id}/remove-agent', [LogisticsController::class, 'removeAgent']);
    Route::post('/shipments/{id}/upload-pod', [LogisticsController::class, 'uploadPOD']);

    // Live Tracking
    Route::get('/live-track/{trackingNumber}', [LogisticsController::class, 'liveTrack'])->name('logistics.live-track');

    // ========== ADD THESE API ROUTES ==========
    // API Routes for AJAX calls
    Route::get('/api/available-agents', [LogisticsController::class, 'getAvailableAgents']);
    Route::get('/api/agents/{agentId}/location', [LogisticsController::class, 'getAgentLocation']);
    Route::post('/api/shipments/{shipment}/location', [LogisticsController::class, 'updateShipmentLocation']);
    // ======================================

    // Other Routes
    Route::get('/agents', [LogisticsController::class, 'agents'])->name('logistics.agents');
    Route::get('/reports', [LogisticsController::class, 'reports'])->name('logistics.reports');
    Route::post('/bulk-create', [LogisticsController::class, 'bulkCreate'])->name('logistics.bulk-create');
    Route::get('/agents-map', [LogisticsController::class, 'getAgentsForMap']);
    Route::get('/track/{trackingNumber}', [LogisticsController::class, 'trackWeb']);
});



// Add this route in your logistics routes group
Route::get('/agents', [LogisticsController::class, 'agents'])->name('logistics.agents.index');


Route::get('/logistics/track/{tracking_number}', [ShipmentController::class, 'liveTrack'])->name('logistics.track');


Route::prefix('logistics')->group(function () {
    // ... existing routes ...

    // Add this route for public tracking
    Route::get('/track/{trackingNumber}', [LogisticsController::class, 'trackWeb'])->name('logistics.track');
});
