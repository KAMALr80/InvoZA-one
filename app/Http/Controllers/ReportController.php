<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Shipment;
use App\Models\Payment;
use App\Models\CustomerWallet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * ================= SALES REPORTS =================
     */

    public function salesReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status', 'all');
        $customerId = $request->get('customer_id');
        $sortBy = $request->get('sort_by', 'sale_date');
        $sortOrder = $request->get('sort_order', 'desc');

        $query = Sale::with(['customer', 'items.product'])
            ->whereBetween('sale_date', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('payment_status', $status);
        }

        if (!empty($customerId)) {
            $query->where('customer_id', $customerId);
        }

        $sales = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();
        $customers = Customer::orderBy('name')->get(['id', 'name']);

        $stats = $this->calculateSalesStats($sales, $startDate, $endDate);

        return view('reports.sales_report', [
            'sales' => $sales,
            'stats' => $stats,
            'customers' => $customers,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'filters' => [
                'status' => $status,
                'customer_id' => $customerId,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
        ]);
    }

    public function exportSalesPDF(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $sales = Sale::with(['customer', 'items.product'])
            ->whereBetween('sale_date', [$from, $to])
            ->get();

        $stats = $this->calculateSalesStats($sales, $from, $to);

        $data = [
            'sales' => $sales,
            'stats' => $stats,
            'from' => $from,
            'to' => $to,
            'startDate' => $from,
            'endDate' => $to,
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
            'filters' => [
                'status' => 'all',
                'customer_id' => null,
            ],
            'customer' => null,
        ];

        $pdf = Pdf::loadView('reports.pdf.sales_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('sales_report_' . date('Y-m-d') . '.pdf');
    }

    public function exportSalesReportCSV(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status', 'all');
        $customerId = $request->get('customer_id');

        $query = Sale::with(['customer', 'items.product'])
            ->whereBetween('sale_date', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('payment_status', $status);
        }

        if (!empty($customerId)) {
            $query->where('customer_id', $customerId);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        $csvData = "Invoice No,Customer Name,Date,Sub Total,Discount,Tax Amount,Grand Total,Payment Status,Items Count\n";

        foreach ($sales as $sale) {
            $itemsCount = $sale->items->sum('quantity');
            $customerName = isset($sale->customer) ? $sale->customer->name : 'Walk-in';
            $csvData .= "\"{$sale->invoice_no}\",";
            $csvData .= "\"{$customerName}\",";
            $csvData .= "\"{$sale->sale_date->format('d-m-Y')}\",";
            $csvData .= "\"{$sale->sub_total}\",";
            $csvData .= "\"{$sale->discount}\",";
            $csvData .= "\"{$sale->tax_amount}\",";
            $csvData .= "\"{$sale->grand_total}\",";
            $csvData .= "\"{$sale->payment_status}\",";
            $csvData .= "\"{$itemsCount}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportSalesReportPDF(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status', 'all');
        $customerId = $request->get('customer_id');
        $sortBy = $request->get('sort_by', 'sale_date');
        $sortOrder = $request->get('sort_order', 'desc');

        $query = Sale::with(['customer', 'items.product'])
            ->whereBetween('sale_date', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('payment_status', $status);
        }

        if (!empty($customerId)) {
            $query->where('customer_id', $customerId);
        }

        $sales = $query->orderBy($sortBy, $sortOrder)->get();
        $stats = $this->calculateSalesStats($sales, $startDate, $endDate);
        $customer = !empty($customerId) ? Customer::find($customerId) : null;

        $data = [
            'sales' => $sales,
            'stats' => $stats,
            'customer' => $customer,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'filters' => [
                'status' => $status,
                'customer_id' => $customerId,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
        ];

        $pdf = Pdf::loadView('reports.pdf.sales_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('sales_report_' . date('Y-m-d') . '.pdf');
    }

    /**
     * ================= SALES STATS CALCULATION =================
     */

    private function calculateSalesStats($sales, $startDate, $endDate)
    {
        $totalRevenue = $sales->sum('grand_total');
        $totalDiscount = $sales->sum('discount');
        $totalTax = $sales->sum('tax_amount');
        $totalOrders = $sales->count();
        $totalItems = 0;

        foreach ($sales as $sale) {
            $totalItems += $sale->items->sum('quantity');
        }

        $paidAmount = $sales->where('payment_status', 'paid')->sum('grand_total');
        $partialAmount = $sales->where('payment_status', 'partial')->sum('grand_total');
        $unpaidAmount = $sales->where('payment_status', 'unpaid')->sum('grand_total');
        $emiAmount = $sales->where('payment_status', 'emi')->sum('grand_total');

        $paidCount = $sales->where('payment_status', 'paid')->count();
        $partialCount = $sales->where('payment_status', 'partial')->count();
        $unpaidCount = $sales->where('payment_status', 'unpaid')->count();
        $emiCount = $sales->where('payment_status', 'emi')->count();

        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_discount' => $totalDiscount,
            'total_tax' => $totalTax,
            'total_orders' => $totalOrders,
            'total_items' => $totalItems,
            'paid_amount' => $paidAmount,
            'partial_amount' => $partialAmount,
            'unpaid_amount' => $unpaidAmount,
            'emi_amount' => $emiAmount,
            'paid_count' => $paidCount,
            'partial_count' => $partialCount,
            'unpaid_count' => $unpaidCount,
            'emi_count' => $emiCount,
            'avg_order_value' => $avgOrderValue,
            'collection_rate' => $totalRevenue > 0 ? round(($paidAmount / $totalRevenue) * 100, 2) : 0,
            'period_days' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1,
        ];
    }

    /**
     * ================= CUSTOMERS REPORTS =================
     */

    public function customers(Request $request)
    {
        $customers = Customer::withCount('sales')->get();

        return view('reports.customers', [
            'customers' => $customers,
            'totalCustomers' => $customers->count(),
            'activeCustomers' => $customers->whereNull('deleted_at')->count(),
            'newThisMonth' => $customers->where('created_at', '>=', now()->startOfMonth())->count(),
        ]);
    }

    public function exportCustomersCSV(Request $request)
    {
        $customers = Customer::all();

        $csvData = "Customer ID,Name,Email,Phone,Status,Created\n";

        foreach ($customers as $customer) {
            $status = $customer->deleted_at ? 'Inactive' : 'Active';
            $csvData .= "\"{$customer->id}\",\"{$customer->name}\",\"{$customer->email}\",\"{$customer->mobile}\",\"{$status}\",\"{$customer->created_at->format('d-m-Y')}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportCustomersPDF(Request $request)
    {
        $customers = Customer::all();

        $totalCustomers = $customers->count();
        $activeCustomers = $customers->whereNull('deleted_at')->count();
        $totalSales = 0;
        $totalPaid = 0;
        $totalWalletBalance = 0;
        $customersWithDue = 0;

        foreach ($customers as $customer) {
            $customerSales = $customer->sales()->sum('grand_total');
            $customerPaid = $customer->payments()->where('status', 'paid')->sum('amount');
            $customerDue = max(0, $customerSales - $customerPaid);

            $totalSales += $customerSales;
            $totalPaid += $customerPaid;
            $totalWalletBalance += $customer->getCurrentWalletBalanceAttribute();

            if ($customerDue > 0) {
                $customersWithDue++;
            }
        }

        $stats = [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'total_sales' => $totalSales,
            'total_paid' => $totalPaid,
            'total_due' => max(0, $totalSales - $totalPaid),
            'total_wallet_balance' => $totalWalletBalance,
            'customers_with_due' => $customersWithDue,
            'collection_rate' => $totalSales > 0 ? round(($totalPaid / $totalSales) * 100, 2) : 0,
        ];

        $data = [
            'customers' => $customers,
            'stats' => $stats,
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
            'filters' => [
                'status' => 'all',
                'search' => '',
                'sort_by' => 'name',
                'sort_order' => 'asc',
            ],
        ];

        $pdf = Pdf::loadView('reports.pdf.customers_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('customers_report_' . date('Y-m-d') . '.pdf');
    }

    /**
     * ================= CUSTOMER DETAILED REPORT =================
     */

    public function customerReport(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        $query = Customer::query();

        if ($status === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($status === 'inactive') {
            $query->whereNotNull('deleted_at');
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('gst_no', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();

        $stats = $this->calculateCustomerStats($customers);

        return view('reports.customers_report', [
            'customers' => $customers,
            'stats' => $stats,
            'filters' => [
                'status' => $status,
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
        ]);
    }

    public function exportCustomerReportCSV(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Customer::query();

        if ($status === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($status === 'inactive') {
            $query->whereNotNull('deleted_at');
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->get();

        $csvData = "Customer ID,Name,Mobile,Email,GST Number,Address,Total Sales,Total Paid,Total Due,Wallet Balance,Status,Created Date\n";

        foreach ($customers as $customer) {
            $totalSales = $customer->sales()->sum('grand_total');
            $totalPaid = $customer->payments()->where('status', 'paid')->sum('amount');
            $totalDue = max(0, $totalSales - $totalPaid);
            $walletBalance = $customer->getCurrentWalletBalanceAttribute();
            $statusText = $customer->deleted_at ? 'Inactive' : 'Active';

            $csvData .= "\"{$customer->id}\",";
            $csvData .= "\"{$customer->name}\",";
            $csvData .= "\"{$customer->mobile}\",";
            $csvData .= "\"{$customer->email}\",";
            $csvData .= "\"{$customer->gst_no}\",";
            $csvData .= "\"" . str_replace('"', '""', $customer->address ?? '') . "\",";
            $csvData .= "\"{$totalSales}\",";
            $csvData .= "\"{$totalPaid}\",";
            $csvData .= "\"{$totalDue}\",";
            $csvData .= "\"{$walletBalance}\",";
            $csvData .= "\"{$statusText}\",";
            $csvData .= "\"{$customer->created_at->format('d-m-Y')}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customer_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportCustomerReportPDF(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        $query = Customer::query();

        if ($status === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($status === 'inactive') {
            $query->whereNotNull('deleted_at');
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('gst_no', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy($sortBy, $sortOrder)->get();
        $stats = $this->calculateCustomerStats($customers);

        $data = [
            'customers' => $customers,
            'stats' => $stats,
            'filters' => [
                'status' => $status,
                'search' => $search,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
        ];

        $pdf = Pdf::loadView('reports.pdf.customer_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('customer_report_' . date('Y-m-d') . '.pdf');
    }

    public function customerSalesReport(Request $request)
    {
        $customerId = $request->get('customer_id');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $query = Sale::with(['customer', 'items.product'])
            ->whereBetween('sale_date', [$startDate, $endDate]);

        if (!empty($customerId)) {
            $query->where('customer_id', $customerId);
        }

        $sales = $query->orderBy('sale_date', 'desc')->paginate(20);

        $customers = Customer::orderBy('name')->get(['id', 'name']);

        $stats = [
            'total_sales' => $sales->sum('grand_total'),
            'total_orders' => $sales->count(),
            'avg_order_value' => $sales->count() > 0 ? $sales->sum('grand_total') / $sales->count() : 0,
            'paid_amount' => $sales->where('payment_status', 'paid')->sum('grand_total'),
            'pending_amount' => $sales->whereIn('payment_status', ['unpaid', 'partial'])->sum('grand_total'),
        ];

        return view('reports.customer_sales_report', [
            'sales' => $sales,
            'stats' => $stats,
            'customers' => $customers,
            'selectedCustomer' => $customerId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function exportCustomerSalesReportCSV(Request $request)
    {
        $customerId = $request->get('customer_id');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $query = Sale::with('customer')
            ->whereBetween('sale_date', [$startDate, $endDate]);

        if (!empty($customerId)) {
            $query->where('customer_id', $customerId);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();

        $csvData = "Invoice No,Customer Name,Date,Grand Total,Payment Status,Payment Method\n";

        foreach ($sales as $sale) {
            $csvData .= "\"{$sale->invoice_no}\",";
            $csvData .= "\"{$sale->customer->name}\",";
            $csvData .= "\"{$sale->sale_date->format('d-m-Y')}\",";
            $csvData .= "\"{$sale->grand_total}\",";
            $csvData .= "\"{$sale->payment_status}\",";
            $csvData .= "\"{$sale->payment_method}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customer_sales_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportCustomerSalesReportPDF(Request $request)
    {
        $customerId = $request->get('customer_id');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $query = Sale::with(['customer', 'items.product'])
            ->whereBetween('sale_date', [$startDate, $endDate]);

        if (!empty($customerId)) {
            $query->where('customer_id', $customerId);
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();
        $customer = !empty($customerId) ? Customer::find($customerId) : null;

        $stats = [
            'total_sales' => $sales->sum('grand_total'),
            'total_orders' => $sales->count(),
            'avg_order_value' => $sales->count() > 0 ? $sales->sum('grand_total') / $sales->count() : 0,
            'paid_amount' => $sales->where('payment_status', 'paid')->sum('grand_total'),
            'pending_amount' => $sales->whereIn('payment_status', ['unpaid', 'partial'])->sum('grand_total'),
        ];

        $data = [
            'sales' => $sales,
            'stats' => $stats,
            'customer' => $customer,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
        ];

        $pdf = Pdf::loadView('reports.pdf.customer_sales_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('customer_sales_report_' . date('Y-m-d') . '.pdf');
    }

    private function calculateCustomerStats($customers)
    {
        $totalCustomers = $customers->total();
        $activeCustomers = 0;
        $totalSales = 0;
        $totalPaid = 0;
        $totalWalletBalance = 0;
        $customersWithDue = 0;

        foreach ($customers as $customer) {
            if (!$customer->deleted_at) {
                $activeCustomers++;
            }

            $customerSales = $customer->sales()->sum('grand_total');
            $customerPaid = $customer->payments()->where('status', 'paid')->sum('amount');
            $customerDue = max(0, $customerSales - $customerPaid);

            $totalSales += $customerSales;
            $totalPaid += $customerPaid;
            $totalWalletBalance += $customer->getCurrentWalletBalanceAttribute();

            if ($customerDue > 0) {
                $customersWithDue++;
            }
        }

        return [
            'total_customers' => $totalCustomers,
            'active_customers' => $activeCustomers,
            'inactive_customers' => $totalCustomers - $activeCustomers,
            'total_sales' => $totalSales,
            'total_paid' => $totalPaid,
            'total_due' => max(0, $totalSales - $totalPaid),
            'total_wallet_balance' => $totalWalletBalance,
            'customers_with_due' => $customersWithDue,
            'collection_rate' => $totalSales > 0 ? round(($totalPaid / $totalSales) * 100, 2) : 0,
        ];
    }
/**
 * ================= INVENTORY REPORTS =================
 */

// Add this method for backward compatibility
public function inventory(Request $request)
{
    // Redirect to the new inventoryReport method
    return $this->inventoryReport($request);
}

public function inventoryReport(Request $request)
{
    $category = $request->get('category', 'all');
    $stockStatus = $request->get('stock_status', 'all');
    $search = $request->get('search', '');
    $sortBy = $request->get('sort_by', 'name');
    $sortOrder = $request->get('sort_order', 'asc');

    $query = Product::query();

    // Category filter
    if ($category !== 'all' && !empty($category)) {
        $query->where('category', $category);
    }

    // Stock status filter
    if ($stockStatus === 'low') {
        $query->where('quantity', '<=', 10);
    } elseif ($stockStatus === 'normal') {
        $query->whereBetween('quantity', [11, 30]);
    } elseif ($stockStatus === 'high') {
        $query->where('quantity', '>', 30);
    }

    // Search filter
    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('product_code', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    $products = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();
    $categories = Product::distinct()->pluck('category')->filter()->values();

    $stats = $this->calculateInventoryStats($products, $query);

    return view('reports.inventory_report', [
        'products' => $products,
        'stats' => $stats,
        'categories' => $categories,
        'filters' => [
            'category' => $category,
            'stock_status' => $stockStatus,
            'search' => $search,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ],
    ]);
}

// Keep the existing export methods as they are
public function exportInventoryReportCSV(Request $request)
{
    $category = $request->get('category', 'all');
    $stockStatus = $request->get('stock_status', 'all');
    $search = $request->get('search', '');

    $query = Product::query();

    if ($category !== 'all' && !empty($category)) {
        $query->where('category', $category);
    }

    if ($stockStatus === 'low') {
        $query->where('quantity', '<=', 10);
    } elseif ($stockStatus === 'normal') {
        $query->whereBetween('quantity', [11, 30]);
    } elseif ($stockStatus === 'high') {
        $query->where('quantity', '>', 30);
    }

    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('product_code', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    $products = $query->orderBy('name')->get();

    $csvData = "Product Code,Product Name,Category,Quantity,Price,Stock Value,Status\n";

    foreach ($products as $product) {
        $stockValue = $product->price * $product->quantity;
        $status = $product->quantity <= 10 ? 'Low Stock' : ($product->quantity <= 30 ? 'Normal' : 'High Stock');
        $categoryName = isset($product->category) ? $product->category : 'Uncategorized';

        $csvData .= "\"{$product->product_code}\",";
        $csvData .= "\"{$product->name}\",";
        $csvData .= "\"{$categoryName}\",";
        $csvData .= "\"{$product->quantity}\",";
        $csvData .= "\"{$product->price}\",";
        $csvData .= "\"{$stockValue}\",";
        $csvData .= "\"{$status}\"\n";
    }

    return response($csvData, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="inventory_report_' . date('Y-m-d') . '.csv"',
    ]);
}

public function exportInventoryReportPDF(Request $request)
{
    $category = $request->get('category', 'all');
    $stockStatus = $request->get('stock_status', 'all');
    $search = $request->get('search', '');
    $sortBy = $request->get('sort_by', 'name');
    $sortOrder = $request->get('sort_order', 'asc');

    $query = Product::query();

    if ($category !== 'all' && !empty($category)) {
        $query->where('category', $category);
    }

    if ($stockStatus === 'low') {
        $query->where('quantity', '<=', 10);
    } elseif ($stockStatus === 'normal') {
        $query->whereBetween('quantity', [11, 30]);
    } elseif ($stockStatus === 'high') {
        $query->where('quantity', '>', 30);
    }

    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('product_code', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    $products = $query->orderBy($sortBy, $sortOrder)->get();
    $stats = $this->calculateInventoryStats($products, $query);

    $data = [
        'products' => $products,
        'stats' => $stats,
        'filters' => [
            'category' => $category,
            'stock_status' => $stockStatus,
            'search' => $search,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ],
        'generated_date' => now()->format('d M Y, h:i A'),
        'company_name' => config('app.name', 'SmartERP'),
    ];

    $pdf = Pdf::loadView('reports.pdf.inventory_report', $data);
    $pdf->setPaper('A4', 'landscape');

    return $pdf->download('inventory_report_' . date('Y-m-d') . '.pdf');
}

private function calculateInventoryStats($products, $query)
{
    $totalProducts = $products->count();
    $totalValue = 0;
    $lowStockCount = 0;
    $normalStockCount = 0;
    $highStockCount = 0;
    $totalQuantity = 0;
    $avgPrice = 0;

    foreach ($products as $product) {
        $totalValue += $product->price * $product->quantity;
        $totalQuantity += $product->quantity;

        if ($product->quantity <= 10) {
            $lowStockCount++;
        } elseif ($product->quantity <= 30) {
            $normalStockCount++;
        } else {
            $highStockCount++;
        }
    }

    $avgPrice = $totalProducts > 0 ? $totalValue / $totalProducts : 0;

    $totalAllProducts = Product::count();
    $totalLowStock = Product::where('quantity', '<=', 10)->count();

    $categoryBreakdown = [];
    $categories = Product::select('category')
        ->selectRaw('COUNT(*) as count')
        ->selectRaw('SUM(quantity) as total_quantity')
        ->selectRaw('SUM(price * quantity) as total_value')
        ->groupBy('category')
        ->get();

    foreach ($categories as $cat) {
        if (isset($cat->category) && !empty($cat->category)) {
            $categoryBreakdown[] = [
                'name' => $cat->category,
                'count' => $cat->count,
                'total_quantity' => $cat->total_quantity,
                'total_value' => $cat->total_value,
            ];
        }
    }

    return [
        'total_products' => $totalProducts,
        'total_value' => $totalValue,
        'total_quantity' => $totalQuantity,
        'avg_price' => $avgPrice,
        'low_stock_count' => $lowStockCount,
        'normal_stock_count' => $normalStockCount,
        'high_stock_count' => $highStockCount,
        'total_all_products' => $totalAllProducts,
        'total_low_stock' => $totalLowStock,
        'category_breakdown' => $categoryBreakdown,
        'stock_health' => $totalProducts > 0 ? round(($highStockCount / $totalProducts) * 100, 2) : 0,
    ];
}

    /**
     * ================= LOGISTICS REPORTS =================
     */

    public function logistics(Request $request)
    {
        $shipments = Shipment::all();
        $totalShipments = $shipments->count();
        $deliveredShipments = $shipments->where('status', 'delivered')->count();

        return view('reports.logistics', [
            'totalShipments' => $totalShipments,
            'deliveredShipments' => $deliveredShipments,
            'pendingShipments' => $totalShipments - $deliveredShipments,
        ]);
    }

    public function exportLogisticsCSV(Request $request)
    {
        $shipments = Shipment::all();

        $csvData = "Shipment ID,Customer,Status,Origin,Destination,Agent,Created\n";

        foreach ($shipments as $shipment) {
            $customerName = isset($shipment->customer) ? $shipment->customer->name : 'N/A';
            $agentName = isset($shipment->agent) ? $shipment->agent->name : 'N/A';
            $csvData .= "\"{$shipment->id}\",\"{$customerName}\",\"{$shipment->status}\",\"{$shipment->origin}\",\"{$shipment->destination}\",\"{$agentName}\",\"{$shipment->created_at->format('d-m-Y')}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="logistics_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportLogisticsPDF(Request $request)
    {
        $shipments = Shipment::all();
        $totalShipments = $shipments->count();
        $deliveredShipments = $shipments->where('status', 'delivered')->count();

        $data = [
            'shipments' => $shipments,
            'totalShipments' => $totalShipments,
            'deliveredShipments' => $deliveredShipments,
            'pendingShipments' => $totalShipments - $deliveredShipments,
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
        ];

        $pdf = Pdf::loadView('reports.pdf.logistics_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('logistics_report_' . date('Y-m-d') . '.pdf');
    }

    /**
     * ================= EMPLOYEE REPORTS =================
     */

    public function employees(Request $request)
    {
        $employees = Employee::all();
        $departments = Employee::distinct()->pluck('department');
        $activeEmployees = $employees->whereNull('deleted_at')->count();

        return view('reports.employees', [
            'employees' => $employees,
            'totalEmployees' => $employees->count(),
            'activeEmployees' => $activeEmployees,
            'departments' => $departments,
        ]);
    }

    public function exportEmployeesCSV(Request $request)
    {
        $employees = Employee::all();

        $csvData = "Employee ID,Name,Email,Department,Position,Joining Date,Status\n";

        foreach ($employees as $employee) {
            $status = $employee->deleted_at ? 'Inactive' : 'Active';
            $joiningDate = isset($employee->joining_date) ? $employee->joining_date->format('d-m-Y') : 'N/A';
            $csvData .= "\"{$employee->id}\",\"{$employee->name}\",\"{$employee->email}\",\"{$employee->department}\",\"{$employee->position}\",\"{$joiningDate}\",\"{$status}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportEmployeesPDF(Request $request)
    {
        $employees = Employee::all();
        $departments = Employee::distinct()->pluck('department');
        $activeEmployees = $employees->whereNull('deleted_at')->count();

        $data = [
            'employees' => $employees,
            'totalEmployees' => $employees->count(),
            'activeEmployees' => $activeEmployees,
            'departments' => $departments,
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
        ];

        $pdf = Pdf::loadView('reports.pdf.employees_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('employees_report_' . date('Y-m-d') . '.pdf');
    }

    /**
     * ================= PURCHASE REPORTS =================
     */

    public function purchases(Request $request)
    {
        $startDate = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('to', now()->format('Y-m-d'));

        $purchases = Purchase::whereBetween('purchase_date', [$startDate, $endDate])->get();

        return view('reports.purchases', [
            'purchases' => $purchases,
            'totalPurchases' => $purchases->sum('total'),
            'totalOrders' => $purchases->count(),
            'averageOrderValue' => $purchases->count() > 0 ? $purchases->sum('total') / $purchases->count() : 0,
            'from' => $startDate,
            'to' => $endDate,
        ]);
    }

    public function exportPurchasesCSV(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $purchases = Purchase::whereBetween('purchase_date', [$from, $to])->get();

        $csvData = "Purchase ID,Vendor,Amount,Status,Date\n";

        foreach ($purchases as $purchase) {
            $vendorName = isset($purchase->vendor_name) ? $purchase->vendor_name : 'N/A';
            $csvData .= "\"{$purchase->id}\",\"{$vendorName}\",\"{$purchase->total}\",\"{$purchase->status}\",\"{$purchase->created_at->format('d-m-Y')}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="purchases_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportPurchasesPDF(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $purchases = Purchase::whereBetween('purchase_date', [$from, $to])->get();

        $data = [
            'purchases' => $purchases,
            'totalPurchases' => $purchases->sum('total'),
            'totalOrders' => $purchases->count(),
            'averageOrderValue' => $purchases->count() > 0 ? $purchases->sum('total') / $purchases->count() : 0,
            'from' => $from,
            'to' => $to,
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
        ];

        $pdf = Pdf::loadView('reports.pdf.purchases_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('purchases_report_' . date('Y-m-d') . '.pdf');
    }

    /**
     * ================= ATTENDANCE REPORTS =================
     */

    public function attendance(Request $request)
    {
        $startDate = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('to', now()->format('Y-m-d'));

        $attendances = Attendance::whereBetween('attendance_date', [$startDate, $endDate])->get();

        return view('reports.attendance', [
            'attendances' => $attendances,
            'totalDays' => $attendances->count(),
            'presentDays' => $attendances->where('status', 'present')->count(),
            'absentDays' => $attendances->where('status', 'absent')->count(),
            'from' => $startDate,
            'to' => $endDate,
        ]);
    }

    public function exportAttendanceCSV(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $attendances = Attendance::whereBetween('attendance_date', [$from, $to])->get();

        $csvData = "Employee ID,Employee Name,Date,Status,Remarks\n";

        foreach ($attendances as $attendance) {
            $employeeId = isset($attendance->employee) ? $attendance->employee->id : 'N/A';
            $employeeName = isset($attendance->employee) ? $attendance->employee->name : 'N/A';
            $attendanceDate = isset($attendance->attendance_date) ? Carbon::parse($attendance->attendance_date)->format('d-m-Y') : 'N/A';
            $remarks = isset($attendance->remarks) ? $attendance->remarks : '';
            $csvData .= "\"{$employeeId}\",\"{$employeeName}\",\"{$attendanceDate}\",\"{$attendance->status}\",\"{$remarks}\"\n";
        }

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportAttendancePDF(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $attendances = Attendance::with('employee')
            ->whereBetween('attendance_date', [$from, $to])
            ->get();

        $data = [
            'attendances' => $attendances,
            'totalDays' => $attendances->count(),
            'presentDays' => $attendances->where('status', 'present')->count(),
            'absentDays' => $attendances->where('status', 'absent')->count(),
            'from' => $from,
            'to' => $to,
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
        ];

        $pdf = Pdf::loadView('reports.pdf.attendance_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('attendance_report_' . date('Y-m-d') . '.pdf');
    }

    /**
     * ================= FINANCIAL REPORTS =================
     */

    public function financial(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now());

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])->get();
        $purchases = Purchase::whereBetween('created_at', [$startDate, $endDate])->get();

        $totalRevenue = $sales->sum('grand_total');
        $totalExpenses = $purchases->sum('total');
        $netProfit = $totalRevenue - $totalExpenses;
        $netProfitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 2) : 0;

        return view('reports.financial', [
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'netProfitMargin' => $netProfitMargin,
            'totalSales' => $totalRevenue,
            'totalOrders' => $sales->count(),
            'avgOrderValue' => $sales->count() > 0 ? $totalRevenue / $sales->count() : 0,
            'totalPurchases' => $totalExpenses,
            'totalPurchaseOrders' => $purchases->count(),
            'avgPurchaseValue' => $purchases->count() > 0 ? $totalExpenses / $purchases->count() : 0,
            'amountReceived' => $sales->where('payment_status', 'paid')->sum('grand_total'),
            'outstandingAmount' => $sales->where('payment_status', '!=', 'paid')->sum('grand_total'),
            'collectionRate' => $totalRevenue > 0 ? round((($sales->where('payment_status', 'paid')->sum('grand_total') / $totalRevenue) * 100), 2) : 0,
            'operatingCashFlow' => $netProfit,
            'liquidityRatio' => '1.5',
            'debtRatio' => '32',
        ]);
    }

    public function exportFinancialCSV(Request $request)
    {
        $sales = Sale::all();
        $purchases = Purchase::all();

        $csvData = "Financial Summary Report\n";
        $csvData .= "Generated: " . now()->format('d-m-Y H:i:s') . "\n\n";
        $csvData .= "Total Revenue,Total Expenses,Net Profit,Profit Margin %\n";
        $profitMargin = $sales->sum('grand_total') > 0 ? round((($sales->sum('grand_total') - $purchases->sum('total')) / $sales->sum('grand_total')) * 100, 2) : 0;
        $csvData .= "\"{$sales->sum('grand_total')}\",\"{$purchases->sum('total')}\",\"" . ($sales->sum('grand_total') - $purchases->sum('total')) . "\",\"{$profitMargin}\"\n";

        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="financial_report_' . date('Y-m-d') . '.csv"',
        ]);
    }

    public function exportFinancialPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now());

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])->get();
        $purchases = Purchase::whereBetween('created_at', [$startDate, $endDate])->get();

        $totalRevenue = $sales->sum('grand_total');
        $totalExpenses = $purchases->sum('total');
        $netProfit = $totalRevenue - $totalExpenses;
        $netProfitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 2) : 0;

        $data = [
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'netProfitMargin' => $netProfitMargin,
            'totalSales' => $totalRevenue,
            'totalOrders' => $sales->count(),
            'avgOrderValue' => $sales->count() > 0 ? $totalRevenue / $sales->count() : 0,
            'totalPurchases' => $totalExpenses,
            'totalPurchaseOrders' => $purchases->count(),
            'avgPurchaseValue' => $purchases->count() > 0 ? $totalExpenses / $purchases->count() : 0,
            'amountReceived' => $sales->where('payment_status', 'paid')->sum('grand_total'),
            'outstandingAmount' => $sales->where('payment_status', '!=', 'paid')->sum('grand_total'),
            'collectionRate' => $totalRevenue > 0 ? round((($sales->where('payment_status', 'paid')->sum('grand_total') / $totalRevenue) * 100), 2) : 0,
            'operatingCashFlow' => $netProfit,
            'liquidityRatio' => '1.5',
            'debtRatio' => '32',
            'startDate' => $startDate->format('d M Y'),
            'endDate' => $endDate->format('d M Y'),
            'generated_date' => now()->format('d M Y, h:i A'),
            'company_name' => config('app.name', 'SmartERP'),
        ];

        $pdf = Pdf::loadView('reports.pdf.financial_report', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('financial_report_' . date('Y-m-d') . '.pdf');
    }
}
