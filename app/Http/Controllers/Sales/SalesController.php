<?php
// app/Http/Controllers/Sales/SalesController.php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\CustomerWallet;
use App\Models\Payment;
use App\Models\EmiPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;
use NumberFormatter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SalesController extends Controller
{
    /* =========================================================
       CONSTANTS
    ========================================================= */
    const PAYMENT_REMARKS = [
        'INVOICE' => 'Direct Invoice Payment',
        'EMI_DOWN' => 'EMI Down Payment',
        'ADVANCE_USED' => 'Wallet Used for Invoice',
        'EXCESS_TO_ADVANCE' => 'Excess to Wallet',
        'ADVANCE_ONLY' => 'Pure Advance Payment',
        'WALLET_ADD' => 'Wallet Add',
        'INVOICE_DUE' => 'Marked as Due'
    ];

    /* =========================================================
       SEND SINGLE INVOICE VIA EMAIL - FIXED
    ========================================================= */
    public function sendInvoice(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required|exists:sales,id',
                'recipient_email' => 'required|email',
                'email_subject' => 'required|string|max:255',
                'email_body' => 'nullable|string'
            ]);

            $sale = Sale::with(['customer', 'items.product'])->findOrFail($request->sale_id);

            // Generate PDF
            $pdf = Pdf::loadView('sales.invoice-pdf', [
                'sale' => $sale,
                'amountInWords' => $this->numberToWords($sale->grand_total)
            ]);

            // Prepare email data
            $subject = $request->email_subject;
            $body = $request->email_body ?? "Dear {$sale->customer->name},\n\nPlease find attached the invoice for your recent purchase.\n\nThank you for your business!";

            // FIXED: Using Mail::raw for plain text email
            Mail::raw($body, function ($message) use ($request, $pdf, $subject, $sale) {
                $message->to($request->recipient_email)
                        ->subject($subject)
                        ->attachData($pdf->output(), "Invoice_{$sale->invoice_no}.pdf", [
                            'mime' => 'application/pdf',
                        ]);
            });

            Log::info("Single email sent successfully", [
                'sale_id' => $sale->id,
                'invoice_no' => $sale->invoice_no,
                'recipient' => $request->recipient_email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Email validation error: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Single email failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       BULK SEND INVOICES VIA EMAIL - FIXED
    ========================================================= */
    public function bulkSendInvoice(Request $request)
    {
        try {
            $request->validate([
                'invoice_ids' => 'required|array|min:1',
                'invoice_ids.*' => 'exists:sales,id',
                'recipient_email' => 'required|email',
                'email_subject_prefix' => 'required|string|max:255',
                'email_body_template' => 'nullable|string'
            ]);

            $invoiceIds = $request->invoice_ids;
            $results = [
                'total' => count($invoiceIds),
                'success' => 0,
                'failed' => 0,
                'failed_invoices' => []
            ];

            // Default template if not provided
            $template = $request->email_body_template ?? "Dear {customer_name},\n\nPlease find attached the invoice #{invoice_no} for your recent purchase.\n\nInvoice Details:\nDate: {invoice_date}\nAmount: ₹{amount}\nDue: ₹{due}\n\nThank you for your business!";

            foreach ($invoiceIds as $saleId) {
                try {
                    $sale = Sale::with(['customer', 'items.product'])->find($saleId);
                    
                    if (!$sale) {
                        $results['failed']++;
                        $results['failed_invoices'][] = "ID: {$saleId} - Not found";
                        continue;
                    }

                    // Calculate due amount
                    $totalPaid = $sale->payments()
                        ->where('status', 'paid')
                        ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
                        ->sum('amount');
                    $dueAmount = $sale->grand_total - $totalPaid;

                    // Generate PDF
                    $pdf = Pdf::loadView('sales.invoice-pdf', [
                        'sale' => $sale,
                        'amountInWords' => $this->numberToWords($sale->grand_total)
                    ]);

                    // Prepare subject and body
                    $subject = $request->email_subject_prefix . " - Invoice #{$sale->invoice_no}";
                    
                    // Replace placeholders in body
                    $body = str_replace(
                        ['{customer_name}', '{invoice_no}', '{invoice_date}', '{amount}', '{due}'],
                        [
                            $sale->customer->name ?? 'Customer',
                            $sale->invoice_no,
                            $sale->sale_date->format('d M Y'),
                            number_format($sale->grand_total, 2),
                            number_format($dueAmount, 2)
                        ],
                        $template
                    );

                    // FIXED: Using Mail::raw for plain text email
                    Mail::raw($body, function ($message) use ($request, $pdf, $subject, $sale) {
                        $message->to($request->recipient_email)
                                ->subject($subject)
                                ->attachData($pdf->output(), "Invoice_{$sale->invoice_no}.pdf", [
                                    'mime' => 'application/pdf',
                                ]);
                    });

                    $results['success']++;
                    
                    Log::info("Bulk email sent for invoice #{$sale->invoice_no}");

                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['failed_invoices'][] = "Invoice #{$sale->invoice_no}: " . $e->getMessage();
                    
                    Log::error("Bulk email failed for sale ID {$saleId}: " . $e->getMessage());
                }
            }

            $message = "Emails sent: {$results['success']} successful, {$results['failed']} failed";
            
            return response()->json([
                'success' => $results['success'] > 0,
                'message' => $message,
                'results' => $results
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Bulk email failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send bulk emails: ' . $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       SEND DUE INVOICE REMINDER (For Single Invoice)
    ========================================================= */
    public function sendDueReminder(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required|exists:sales,id',
                'recipient_email' => 'required|email'
            ]);

            $sale = Sale::with('customer')->findOrFail($request->sale_id);

            // Calculate due amount
            $totalPaid = $sale->payments()
                ->where('status', 'paid')
                ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
                ->sum('amount');
            $dueAmount = $sale->grand_total - $totalPaid;

            // Generate PDF
            $pdf = Pdf::loadView('sales.invoice-pdf', [
                'sale' => $sale,
                'amountInWords' => $this->numberToWords($sale->grand_total)
            ]);

            // Create reminder subject and body
            $subject = "Payment Reminder: Invoice #{$sale->invoice_no} - Due: ₹" . number_format($dueAmount, 2);
            $body = "Dear {$sale->customer->name},\n\n"
                  . "This is a friendly reminder that you have an outstanding invoice.\n\n"
                  . "Invoice #: {$sale->invoice_no}\n"
                  . "Date: {$sale->sale_date->format('d M Y')}\n"
                  . "Total Amount: ₹" . number_format($sale->grand_total, 2) . "\n"
                  . "Due Amount: ₹" . number_format($dueAmount, 2) . "\n\n"
                  . "Please make the payment at your earliest convenience.\n\n"
                  . "Thank you for your business!";

            // Send email
            Mail::raw($body, function ($message) use ($request, $pdf, $subject, $sale) {
                $message->to($request->recipient_email)
                        ->subject($subject)
                        ->attachData($pdf->output(), "Invoice_{$sale->invoice_no}.pdf", [
                            'mime' => 'application/pdf',
                        ]);
            });

            return response()->json([
                'success' => true,
                'message' => "Reminder sent for Invoice #{$sale->invoice_no}"
            ]);

        } catch (\Exception $e) {
            Log::error('Due reminder failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminder: ' . $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       BULK SEND DUE REMINDERS (For Multiple Invoices)
    ========================================================= */
    public function bulkSendDueReminders(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'invoice_ids' => 'required|array|min:1',
                'invoice_ids.*' => 'exists:sales,id',
                'recipient_email' => 'required|email'
            ]);

            $customer = Customer::find($request->customer_id);
            $invoiceIds = $request->invoice_ids;
            
            $results = [
                'total' => count($invoiceIds),
                'success' => 0,
                'failed' => 0,
                'total_due' => 0
            ];

            $invoiceList = [];

            foreach ($invoiceIds as $saleId) {
                try {
                    $sale = Sale::with('customer')->find($saleId);
                    
                    if (!$sale) {
                        $results['failed']++;
                        continue;
                    }

                    // Calculate due
                    $totalPaid = $sale->payments()
                        ->where('status', 'paid')
                        ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
                        ->sum('amount');
                    $dueAmount = $sale->grand_total - $totalPaid;
                    
                    $results['total_due'] += $dueAmount;
                    
                    $invoiceList[] = [
                        'no' => $sale->invoice_no,
                        'date' => $sale->sale_date->format('d M Y'),
                        'total' => $sale->grand_total,
                        'due' => $dueAmount
                    ];
                    
                    // Generate and attach PDF for this invoice
                    $pdf = Pdf::loadView('sales.invoice-pdf', [
                        'sale' => $sale,
                        'amountInWords' => $this->numberToWords($sale->grand_total)
                    ]);

                    // Create reminder email
                    $subject = "Payment Reminder: Invoice #{$sale->invoice_no} - Due: ₹" . number_format($dueAmount, 2);
                    $body = "Dear {$customer->name},\n\n"
                          . "This is a friendly reminder that you have an outstanding invoice.\n\n"
                          . "Invoice #: {$sale->invoice_no}\n"
                          . "Date: {$sale->sale_date->format('d M Y')}\n"
                          . "Total Amount: ₹" . number_format($sale->grand_total, 2) . "\n"
                          . "Due Amount: ₹" . number_format($dueAmount, 2) . "\n\n"
                          . "Please make the payment at your earliest convenience.\n\n"
                          . "Thank you for your business!";

                    // Send email
                    Mail::raw($body, function ($message) use ($request, $pdf, $subject, $sale) {
                        $message->to($request->recipient_email)
                                ->subject($subject)
                                ->attachData($pdf->output(), "Invoice_{$sale->invoice_no}.pdf", [
                                    'mime' => 'application/pdf',
                                ]);
                    });

                    $results['success']++;

                } catch (\Exception $e) {
                    $results['failed']++;
                    Log::error("Bulk due reminder failed for sale ID {$saleId}: " . $e->getMessage());
                }
            }

            $message = "Reminders sent: {$results['success']} successful, {$results['failed']} failed";
            
            return response()->json([
                'success' => $results['success'] > 0,
                'message' => $message,
                'results' => $results,
                'invoice_list' => $invoiceList
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk due reminders failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert number to words
     */
    private function numberToWords($num)
    {
        $ones = [
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen',
            15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
            60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
        ];
        
        $num = (int)$num; // Convert to integer for words
        
        if ($num < 20) return $ones[$num];
        if ($num < 100) return $ones[floor($num/10)*10] . ($num%10 > 0 ? ' ' . $ones[$num%10] : '');
        if ($num < 1000) return $ones[floor($num/100)] . ' Hundred' . ($num%100 > 0 ? ' ' . $this->numberToWords($num%100) : '');
        if ($num < 100000) return $this->numberToWords(floor($num/1000)) . ' Thousand' . ($num%1000 > 0 ? ' ' . $this->numberToWords($num%1000) : '');
        if ($num < 10000000) return $this->numberToWords(floor($num/100000)) . ' Lakh' . ($num%100000 > 0 ? ' ' . $this->numberToWords($num%100000) : '');
        return $this->numberToWords(floor($num/10000000)) . ' Crore' . ($num%10000000 > 0 ? ' ' . $this->numberToWords($num%10000000) : '');
    }

    /* =========================================================
       DATATABLE FOR AJAX
    ========================================================= */
    public function datatable()
    {
        $sales = Sale::with('customer')->select('sales.*');

        return DataTables::of($sales)
            ->addColumn('customer_name', function ($sale) {
                return $sale->customer->name ?? 'N/A';
            })
            ->addColumn('customer_mobile', function ($sale) {
                return $sale->customer->mobile ?? 'N/A';
            })
            ->addColumn('status_badge', function ($sale) {
                $badgeClass = match ($sale->payment_status) {
                    'paid' => 'success',
                    'partial' => 'warning',
                    'emi' => 'info',
                    default => 'danger'
                };
                return "<span class='badge bg-{$badgeClass}'>{$sale->payment_status}</span>";
            })
            ->addColumn('actions', function ($sale) {
                return view('sales.partials.actions', compact('sale'))->render();
            })
            ->addColumn('amount_formatted', function ($sale) {
                return '₹' . number_format($sale->grand_total, 2);
            })
            ->rawColumns(['status_badge', 'actions'])
            ->make(true);
    }

    /* =========================================================
       STATS FOR DASHBOARD
    ========================================================= */
    public function stats()
    {
        $totalInvoices = Sale::count();
        $totalRevenue = Sale::sum('grand_total');
        $totalPaid = Payment::where('status', 'paid')
            ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
            ->sum('amount');
        $totalDue = $totalRevenue - $totalPaid;

        $recentSales = Sale::with('customer')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'invoice_no' => $sale->invoice_no,
                    'customer' => $sale->customer->name ?? 'N/A',
                    'amount' => $sale->grand_total,
                    'status' => $sale->payment_status,
                    'date' => $sale->sale_date->format('d M Y')
                ];
            });

        return response()->json([
            'total_invoices' => $totalInvoices,
            'total_revenue' => $totalRevenue,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue,
            'recent_sales' => $recentSales,
            'collection_rate' => $totalRevenue > 0 ? round(($totalPaid / $totalRevenue) * 100, 2) : 0
        ]);
    }

    /* =========================================================
       AJAX CUSTOMER CREATE
    ========================================================= */
    public function ajaxStore(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:customers,mobile',
            'email'  => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'gst_no' => 'nullable|string|max:50'
        ]);

        try {
            $customer = Customer::create([
                'name'    => $request->name,
                'mobile'  => $request->mobile,
                'email'   => $request->email,
                'address' => $request->address,
                'gst_no'  => $request->gst_no,
                'open_balance' => 0,
                'wallet_balance' => 0
            ]);

            Log::info("New customer created via AJAX: ID={$customer->id}, Name={$customer->name}");

            return response()->json([
                'success'  => true,
                'customer' => $customer,
                'message'  => 'Customer created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('AJAX Customer Create Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       SALES LIST
    ========================================================= */
    public function index(Request $request)
    {
        $query = Sale::with('customer');

        // Filter by status
        if ($request->status && $request->status != 'all') {
            $query->where('payment_status', $request->status);
        }

        // Search by invoice no or customer
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_no', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('customer', function ($cq) use ($request) {
                        $cq->where('name', 'LIKE', "%{$request->search}%")
                            ->orWhere('mobile', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // Date range filter
        if ($request->from_date) {
            $query->whereDate('sale_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('sale_date', '<=', $request->to_date);
        }

        $sales = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Sale::count(),
            'paid' => Sale::where('payment_status', 'paid')->count(),
            'partial' => Sale::where('payment_status', 'partial')->count(),
            'unpaid' => Sale::where('payment_status', 'unpaid')->count(),
            'emi' => Sale::where('payment_status', 'emi')->count()
        ];

        return view('sales.index', compact('sales', 'stats', 'request'));
    }

    /* =========================================================
       CREATE SALE PAGE
    ========================================================= */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products  = Product::where('quantity', '>', 0)->orderBy('name')->get();

        // Generate unique invoice token
        $invoice_token = uniqid() . '_' . time();

        return view('sales.create', compact('customers', 'products', 'invoice_token'));
    }

    /* =========================================================
       STORE SALE
    ========================================================= */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'        => 'required|exists:customers,id',
            'invoice_token'      => 'required|string|unique:sales,invoice_token',
            'items.product_id'   => 'required|array|min:1',
            'items.quantity'     => 'required|array|min:1',
            'items.price'        => 'required|array|min:1',
            'discount'           => 'nullable|numeric|min:0|max:9999999.99',
            'tax'                => 'nullable|numeric|min:0|max:100'
        ]);

        // Check for duplicate submission
        $existingSale = Sale::where('invoice_token', $request->invoice_token)->first();
        if ($existingSale) {
            return redirect()
                ->route('sales.show', $existingSale->id)
                ->with('info', 'Invoice already exists');
        }

        try {
            DB::transaction(function () use ($request) {
                // Calculate totals
                $subTotal = $this->calculateSubTotal($request->items);
                $discount = (float) ($request->discount ?? 0);
                $tax = (float) ($request->tax ?? 0);
                $taxAmount = $subTotal * $tax / 100;
                $grandTotal = $subTotal - $discount + $taxAmount;

                // Lock customer for update
                $customer = Customer::lockForUpdate()->findOrFail($request->customer_id);

                // Generate invoice number
                $lastId = Sale::max('id') ?? 0;
                $invoiceNo = 'INV-' . date('Y') . '-' . str_pad(($lastId + 1), 6, '0', STR_PAD_LEFT);

                // Create sale
                $sale = Sale::create([
                    'customer_id'    => $customer->id,
                    'invoice_no'     => $invoiceNo,
                    'invoice_token'  => $request->invoice_token,
                    'sale_date'      => now(),
                    'sub_total'      => $subTotal,
                    'discount'       => $discount,
                    'tax'            => $tax,
                    'tax_amount'     => $taxAmount,
                    'grand_total'    => $grandTotal,
                    'payment_status' => 'unpaid',
                    'paid_amount'    => 0
                ]);

                // Add items and update stock
                foreach ($request->items['product_id'] as $i => $productId) {
                    $qty = (int) $request->items['quantity'][$i];
                    $price = (float) $request->items['price'][$i];

                    $product = Product::lockForUpdate()->findOrFail($productId);

                    if ($product->quantity < $qty) {
                        throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->quantity}");
                    }

                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $productId,
                        'quantity'   => $qty,
                        'price'      => $price,
                        'total'      => $qty * $price,
                    ]);

                    $product->decrement('quantity', $qty);
                }

                Log::info("Sale created successfully: ID={$sale->id}, Invoice={$sale->invoice_no}");
            });

            return redirect()
                ->route('sales.index')
                ->with('success', 'Sale created successfully');
        } catch (\Exception $e) {
            Log::error('Sale Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error creating sale: ' . $e->getMessage());
        }
    }

    /* =========================================================
       SHOW SALE
    ========================================================= */
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product', 'payments']);

        // Get EMI details if exists
        $emiData = $this->getEmiData($sale);

        // Calculate payment summary
        $paymentSummary = $this->calculatePaymentSummary($sale);

        return view('sales.show', compact('sale', 'emiData', 'paymentSummary'));
    }

    /* =========================================================
       VIEW SALE (ALIAS FOR SHOW)
    ========================================================= */
    public function view(Sale $sale)
    {
        return $this->show($sale);
    }

    /* =========================================================
       EDIT SALE
    ========================================================= */
    public function edit(Sale $sale)
    {
        // Can't edit paid or EMI invoices
        if (in_array($sale->payment_status, ['paid', 'emi'])) {
            return redirect()
                ->route('sales.show', $sale)
                ->with('error', 'Cannot edit a paid or EMI invoice');
        }

        $sale->load('items.product');
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    /* =========================================================
       UPDATE SALE
    ========================================================= */
    public function update(Request $request, Sale $sale)
    {
        // Can't update paid or EMI invoices
        if (in_array($sale->payment_status, ['paid', 'emi'])) {
            return redirect()
                ->route('sales.show', $sale)
                ->with('error', 'Cannot update a paid or EMI invoice');
        }

        $request->validate([
            'items.product_id' => 'required|array|min:1',
            'items.quantity'   => 'required|array|min:1',
            'items.price'      => 'required|array|min:1',
            'discount'         => 'nullable|numeric|min:0',
            'tax'              => 'nullable|numeric|min:0|max:100'
        ]);

        try {
            DB::transaction(function () use ($request, $sale) {
                // Calculate new totals
                $subTotal = $this->calculateSubTotal($request->items);
                $discount = (float) ($request->discount ?? 0);
                $tax = (float) ($request->tax ?? 0);
                $taxAmount = $subTotal * $tax / 100;
                $grandTotal = $subTotal - $discount + $taxAmount;

                // Restore old stock
                foreach ($sale->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }

                // Delete old items
                $sale->items()->delete();

                // Add new items and deduct stock
                foreach ($request->items['product_id'] as $i => $productId) {
                    $qty = (int) $request->items['quantity'][$i];
                    $price = (float) $request->items['price'][$i];

                    $product = Product::lockForUpdate()->findOrFail($productId);

                    if ($product->quantity < $qty) {
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }

                    SaleItem::create([
                        'sale_id'    => $sale->id,
                        'product_id' => $productId,
                        'quantity'   => $qty,
                        'price'      => $price,
                        'total'      => $qty * $price,
                    ]);

                    $product->decrement('quantity', $qty);
                }

                // Update sale
                $sale->update([
                    'sub_total'   => $subTotal,
                    'discount'    => $discount,
                    'tax'         => $tax,
                    'tax_amount'  => $taxAmount,
                    'grand_total' => $grandTotal,
                ]);

                // Recalculate invoice status
                $this->recalculateInvoiceStatus($sale->id);

                Log::info("Sale updated successfully: ID={$sale->id}");
            });

            return redirect()
                ->route('sales.show', $sale)
                ->with('success', 'Invoice updated successfully');
        } catch (\Exception $e) {
            Log::error('Sale Update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'sale_id' => $sale->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    /* =========================================================
       FIND AFFECTED INVOICES
    ========================================================= */
    private function findAffectedInvoices($sale)
    {
        Log::info("========== 🔍 FIND AFFECTED INVOICES ==========");
        Log::info("Deleting invoice ID: {$sale->id}, Invoice #: {$sale->invoice_no}");

        $affectedIds = [];

        foreach ($sale->payments as $payment) {
            Log::info("Checking payment ID: {$payment->id}, Remarks: {$payment->remarks}");

            // Case 1: Ye payment wallet USE kar rahi hai (ADVANCE_USED)
            if ($payment->remarks === 'ADVANCE_USED' && $payment->source_wallet_id) {
                $originalCreditWalletId = $payment->source_wallet_id;
                Log::info("  → This payment uses credit wallet ID: {$originalCreditWalletId}");

                // Is original wallet ka use aur kis kis invoice ne kiya?
                $otherUsers = Payment::where('source_wallet_id', $originalCreditWalletId)
                    ->where('sale_id', '!=', $sale->id)
                    ->where('remarks', 'ADVANCE_USED')
                    ->pluck('sale_id')
                    ->toArray();

                Log::info("  → Other invoices using this credit: " . json_encode($otherUsers));
                $affectedIds = array_merge($affectedIds, $otherUsers);
            }

            // Case 2: Ye payment WALLET ADD kar rahi hai (EXCESS_TO_ADVANCE/ADVANCE_ONLY/WALLET_ADD)
            elseif (in_array($payment->remarks, ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY', 'WALLET_ADD']) && $payment->wallet_id) {
                Log::info("  → This payment created wallet ID: {$payment->wallet_id}");

                $usersOfThisWallet = Payment::where('source_wallet_id', $payment->wallet_id)
                    ->where('sale_id', '!=', $sale->id)
                    ->where('remarks', 'ADVANCE_USED')
                    ->pluck('sale_id')
                    ->toArray();

                Log::info("  → Invoices using this wallet: " . json_encode($usersOfThisWallet));
                $affectedIds = array_merge($affectedIds, $usersOfThisWallet);
            }
        }

        $uniqueIds = array_unique($affectedIds);
        Log::info("✅ Final affected invoices: " . json_encode($uniqueIds));
        Log::info("========== 🔍 FIND AFFECTED INVOICES END ==========");

        return $uniqueIds;
    }

    /* =========================================================
       DELETE INVOICE WITH ALL PAYMENTS - FIXED VERSION
    ========================================================= */
    public function deleteWithPayments($saleId)
    {
        DB::beginTransaction();

        try {
            $sale = Sale::with(['items', 'payments', 'customer'])->lockForUpdate()->findOrFail($saleId);
            $customer = $sale->customer;

            if (!$customer) {
                throw new \Exception('Customer not found for this sale');
            }

            Log::info("========== 🗑️ DELETE WITH PAYMENTS STARTED ==========");
            Log::info("Deleting invoice #{$sale->invoice_no} (ID: {$saleId})");
            Log::info("Customer: {$customer->name} (ID: {$customer->id})");

            // ========== STEP 1: GET CURRENT BALANCES ==========
            $currentWalletBalance = $this->getCurrentWalletBalance($customer->id);
            $currentOpenBalance = $customer->open_balance;

            Log::info("Current balances:");
            Log::info("  • Wallet balance: ₹{$currentWalletBalance}");
            Log::info("  • Open balance: ₹{$currentOpenBalance}");

            // Trackers
            $walletAdjustment = 0;
            $openBalanceAdjustment = 0;
            $affectedInvoiceIds = [];
            $processedWallets = [];
            $totalCashToWallet = 0;

            // ========== STEP 2: FIND AFFECTED INVOICES (jo advance use karte hain) ==========
            Log::info("🔍 STEP 2: Finding affected invoices...");

            foreach ($sale->payments as $payment) {
                // Case: Is invoice ne kisi aur ka advance use kiya hai
                if ($payment->remarks === 'ADVANCE_USED' && $payment->source_wallet_id) {
                    $otherUsers = Payment::where('source_wallet_id', $payment->source_wallet_id)
                        ->where('sale_id', '!=', $saleId)
                        ->where('remarks', 'ADVANCE_USED')
                        ->pluck('sale_id')
                        ->toArray();

                    $affectedInvoiceIds = array_merge($affectedInvoiceIds, $otherUsers);
                    Log::info("  • Source wallet ID {$payment->source_wallet_id} used in invoices: " . json_encode($otherUsers));
                }

                // Case: Is invoice ne excess diya jo doosron ne use kiya
                if (in_array($payment->remarks, ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY', 'WALLET_ADD']) && $payment->wallet_id) {
                    $users = Payment::where('source_wallet_id', $payment->wallet_id)
                        ->where('remarks', 'ADVANCE_USED')
                        ->pluck('sale_id')
                        ->toArray();

                    $affectedInvoiceIds = array_merge($affectedInvoiceIds, $users);
                    Log::info("  • Credit wallet ID {$payment->wallet_id} used in invoices: " . json_encode($users));
                }
            }

            $affectedInvoiceIds = array_unique($affectedInvoiceIds);
            Log::info("Total affected invoices: " . count($affectedInvoiceIds));

            // ========== STEP 3: COLLECT CASH FROM AFFECTED INVOICES ==========
            Log::info("🔍 STEP 3: Collecting cash from affected invoices...");

            foreach ($affectedInvoiceIds as $invId) {
                $inv = Sale::with('payments')->find($invId);
                if ($inv) {
                    foreach ($inv->payments as $payment) {
                        if (in_array($payment->remarks, ['INVOICE', 'EMI_DOWN'])) {
                            $totalCashToWallet += $payment->amount;
                            Log::info("  • Invoice #{$inv->invoice_no}: Cash ₹{$payment->amount}");
                        }
                    }
                }
            }

            Log::info("Total cash to add to wallet: ₹{$totalCashToWallet}");

            // ========== STEP 4: PROCESS MAIN INVOICE KE PAYMENTS ==========
            Log::info("🔍 STEP 4: Processing main invoice payments...");

            foreach ($sale->payments as $payment) {
                Log::info("-----------------------------------");
                Log::info("Processing payment ID: {$payment->id}");
                Log::info("  • Remarks: {$payment->remarks}");
                Log::info("  • Amount: ₹{$payment->amount}");

                // CASE 1: CASH/INVOICE PAYMENT
                if (in_array($payment->remarks, ['INVOICE', 'EMI_DOWN'])) {
                    $openBalanceAdjustment += $payment->amount;
                    Log::info("  → Type: CASH PAYMENT");
                    Log::info("  → Effect: +₹{$payment->amount} to open balance");
                }

                // CASE 2: WALLET ADD/EXCESS (Credit) - Ye doosron ne use kiya hoga
                elseif (in_array($payment->remarks, ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY', 'WALLET_ADD'])) {
                    if ($payment->wallet_id && !in_array($payment->wallet_id, $processedWallets)) {
                        // Ye credit delete ho raha hai - wallet balance GHATEGA
                        $walletAdjustment -= $payment->amount;
                        $processedWallets[] = $payment->wallet_id;

                        Log::info("  → Type: WALLET CREDIT");
                        Log::info("  → Effect: -₹{$payment->amount} to wallet balance");

                        CustomerWallet::where('id', $payment->wallet_id)->delete();
                        Log::info("  ✅ Deleted wallet credit ID: {$payment->wallet_id}");
                    }
                }

                // CASE 3: WALLET USED (Debit) - Isne kisi aur ka advance use kiya
                elseif ($payment->remarks === 'ADVANCE_USED') {
                    if ($payment->wallet_id && !in_array($payment->wallet_id, $processedWallets)) {
                        // Ye debit delete ho raha hai - wallet balance BADHEGA
                        $walletAdjustment += $payment->amount;
                        $processedWallets[] = $payment->wallet_id;

                        Log::info("  → Type: WALLET DEBIT");
                        Log::info("  → Effect: +₹{$payment->amount} to wallet balance");

                        CustomerWallet::where('id', $payment->wallet_id)->delete();
                        Log::info("  ✅ Deleted wallet debit ID: {$payment->wallet_id}");
                    }

                    $openBalanceAdjustment += $payment->amount;
                    Log::info("  → Also: +₹{$payment->amount} to open balance");
                }

                $payment->delete();
                Log::info("  ✅ Deleted payment record");
            }

            // ========== STEP 5: PROCESS AFFECTED INVOICES (UNHE DUE KARO) ==========
            if (!empty($affectedInvoiceIds)) {
                Log::info("🔍 STEP 5: Processing affected invoices...");

                foreach ($affectedInvoiceIds as $affectedId) {
                    $affectedSale = Sale::with('payments')->find($affectedId);
                    if ($affectedSale) {
                        Log::info("-----------------------------------");
                        Log::info("Processing affected invoice #{$affectedSale->invoice_no}");

                        // Affected invoice ke saare payments delete karo
                        foreach ($affectedSale->payments as $payment) {
                            Log::info("  Deleting payment ID: {$payment->id} (₹{$payment->amount})");

                            // Agar wallet payment hai to wallet adjustment karo
                            if ($payment->remarks === 'ADVANCE_USED' && $payment->wallet_id) {
                                if (!in_array($payment->wallet_id, $processedWallets)) {
                                    // Debit delete - wallet balance BADHEGA
                                    $walletAdjustment += $payment->amount;
                                    $processedWallets[] = $payment->wallet_id;
                                    CustomerWallet::where('id', $payment->wallet_id)->delete();
                                    Log::info("    → Wallet debit deleted: +₹{$payment->amount}");
                                }
                            }

                            // EXCESS_TO_ADVANCE payments - ye affected invoice ki apni excess hai
                            if (in_array($payment->remarks, ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY', 'WALLET_ADD']) && $payment->wallet_id) {
                                if (!in_array($payment->wallet_id, $processedWallets)) {
                                    // Credit delete - wallet balance GHATEGA
                                    $walletAdjustment -= $payment->amount;
                                    $processedWallets[] = $payment->wallet_id;
                                    CustomerWallet::where('id', $payment->wallet_id)->delete();
                                    Log::info("    → Wallet credit deleted: -₹{$payment->amount}");
                                }
                            }

                            // Note: Cash payments already counted in STEP 3
                            $payment->delete();
                        }

                        // Affected invoice ko DUE karo
                        $affectedSale->payment_status = 'unpaid';
                        $affectedSale->paid_amount = 0;
                        $affectedSale->save();

                        Log::info("  ✅ Invoice #{$affectedSale->invoice_no} marked as DUE");

                        // Open balance adjustment for affected invoice
                        $openBalanceAdjustment += $affectedSale->grand_total;
                        Log::info("  → Added ₹{$affectedSale->grand_total} to open balance");
                    }
                }
            }

            // ========== STEP 6: ADD CASH TO WALLET ==========
            if ($totalCashToWallet > 0) {
                Log::info("🔍 STEP 6: Adding ₹{$totalCashToWallet} cash to wallet...");

                $currentBalance = $this->getCurrentWalletBalance($customer->id);
                $newBalance = $currentBalance + $totalCashToWallet;

                $wallet = CustomerWallet::create([
                    'customer_id' => $customer->id,
                    'type' => 'credit',
                    'amount' => $totalCashToWallet,
                    'balance' => $newBalance,
                    'reference' => 'Cash recovered from affected invoices'
                ]);

                // Reset wallet adjustment since we're creating new credit
                $walletAdjustment = $totalCashToWallet;

                Log::info("  ✅ Created wallet credit ID: {$wallet->id} for ₹{$totalCashToWallet}");
            }

            // ========== STEP 7: RESTORE STOCK ==========
            Log::info("🔍 STEP 7: Restoring stock...");

            foreach ($sale->items as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                    Log::info("  ✅ Restored {$item->quantity} units");
                }
                $item->delete();
            }

            // ========== STEP 8: DELETE EMI PLAN ==========
            if ($sale->emiPlan) {
                $sale->emiPlan->delete();
                Log::info("  ✅ EMI plan deleted");
            }

            // ========== STEP 9: DELETE MAIN SALE ==========
            $sale->delete();
            Log::info("  ✅ Main sale deleted");

            // ========== STEP 10: CALCULATE FINAL BALANCES ==========
            $newWalletBalance = $currentWalletBalance + $walletAdjustment;
            $newOpenBalance = $currentOpenBalance + $openBalanceAdjustment;

            $newWalletBalance = max(0, $newWalletBalance);
            $newOpenBalance = max(0, $newOpenBalance);

            // ========== STEP 11: UPDATE CUSTOMER ==========
            $customer->wallet_balance = $newWalletBalance;
            $customer->open_balance = $newOpenBalance;
            $customer->save();

            Log::info("Final balances:");
            Log::info("  • Wallet: ₹{$newWalletBalance}");
            Log::info("  • Open: ₹{$newOpenBalance}");

            DB::commit();

            $message = "✅ Invoice #{$sale->invoice_no} deleted successfully!\n";
            if (!empty($affectedInvoiceIds)) {
                $message .= "📄 " . count($affectedInvoiceIds) . " affected invoice(s) marked as DUE\n";
            }
            $message .= "💰 Cash added to wallet: ₹" . number_format($totalCashToWallet, 2) . "\n";
            $message .= "💼 New wallet balance: ₹" . number_format($newWalletBalance, 2) . "\n";
            $message .= "📊 New open balance: ₹" . number_format($newOpenBalance, 2);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'deleted_invoice' => $sale->invoice_no,
                    'affected_invoices' => $affectedInvoiceIds,
                    'affected_count' => count($affectedInvoiceIds),
                    'cash_to_wallet' => $totalCashToWallet,
                    'new_wallet_balance' => $newWalletBalance,
                    'new_open_balance' => $newOpenBalance
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ DELETE ERROR: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    /* =========================================================
       HELPER METHODS
    ========================================================= */

    private function calculateSubTotal($items): float
    {
        $subTotal = 0;
        foreach ($items['product_id'] as $i => $productId) {
            $qty = (int) $items['quantity'][$i];
            $price = (float) $items['price'][$i];
            $subTotal += ($qty * $price);
        }
        return $subTotal;
    }

    private function getCurrentWalletBalance($customerId): float
    {
        $lastWallet = CustomerWallet::where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->first();

        return $lastWallet ? (float) $lastWallet->balance : 0.00;
    }

    private function recalculateInvoiceStatus($saleId): void
    {
        $sale = Sale::find($saleId);
        if (!$sale) return;

        $totalPaid = Payment::where('sale_id', $saleId)
            ->where('status', 'paid')
            ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
            ->sum('amount');

        $remaining = $sale->grand_total - $totalPaid;

        $newStatus = match (true) {
            $remaining <= 0.01 => 'paid',
            $totalPaid > 0 => 'partial',
            default => 'unpaid'
        };

        $sale->update([
            'payment_status' => $newStatus,
            'paid_amount' => $totalPaid
        ]);

        Log::info("Invoice #{$sale->invoice_no} status updated to {$newStatus} (Paid: ₹{$totalPaid})");
    }

    private function recalculateAllCustomerInvoices($customerId): void
    {
        $invoices = Sale::where('customer_id', $customerId)->get();

        foreach ($invoices as $invoice) {
            $this->recalculateInvoiceStatus($invoice->id);
        }
    }

    private function getEmiData($sale): ?array
    {
        $emi = EmiPlan::where('sale_id', $sale->id)
            ->where('status', 'running')
            ->first();

        if (!$emi) return null;

        $total = $emi->emi_amount * $emi->months;
        $paid = Payment::where('sale_id', $sale->id)
            ->where('method', 'emi')
            ->sum('amount');
        $remaining = max($total - $paid, 0);
        $nextPay = min($emi->emi_amount, $remaining);

        return [
            'emi' => $emi,
            'total' => $total,
            'paid' => $paid,
            'remaining' => $remaining,
            'nextPay' => $nextPay,
            'completed' => $remaining <= 0,
            'progress' => $total > 0 ? round(($paid / $total) * 100, 2) : 0
        ];
    }

    private function calculatePaymentSummary($sale): array
    {
        $payments = $sale->payments;

        return [
            'total_paid' => $payments->where('status', 'paid')->sum('amount'),
            'cash_paid' => $payments->where('status', 'paid')->whereIn('remarks', ['INVOICE', 'EMI_DOWN'])->sum('amount'),
            'wallet_used' => $payments->where('status', 'paid')->where('remarks', 'ADVANCE_USED')->sum('amount'),
            'wallet_added' => $payments->where('status', 'paid')->whereIn('remarks', ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY', 'WALLET_ADD'])->sum('amount'),
            'payment_count' => $payments->count(),
            'due' => $sale->grand_total - $payments->where('status', 'paid')->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])->sum('amount')
        ];
    }

    /* =========================================================
       PRINT INVOICE - HTML Version for Browser Printing
    ========================================================= */
    public function print(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);

        // Get payment summary
        $totalPaid = $sale->payments()
            ->where('status', 'paid')
            ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
            ->sum('amount');

        $remaining = $sale->grand_total - $totalPaid;

        return view('sales.print', compact('sale', 'totalPaid', 'remaining'));
    }

    /* =========================================================
       INVOICE PDF - Download PDF Version
    ========================================================= */
    public function invoice(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);

        // Convert amount to words
        try {
            $formatter = new \NumberFormatter('en_IN', \NumberFormatter::SPELLOUT);
            $amountInWords = ucfirst($formatter->format($sale->grand_total)) . ' Rupees Only';
        } catch (\Exception $e) {
            $amountInWords = 'Rupees ' . number_format($sale->grand_total, 2) . ' Only';
        }

        // Get payment summary
        $totalPaid = $sale->payments()
            ->where('status', 'paid')
            ->whereIn('remarks', ['INVOICE', 'EMI_DOWN', 'ADVANCE_USED'])
            ->sum('amount');

        $pdf = Pdf::loadView('sales.invoice', [
            'sale' => $sale,
            'amountInWords' => $amountInWords,
            'totalPaid' => $totalPaid,
            'remaining' => $sale->grand_total - $totalPaid
        ]);

        return $pdf->stream('Invoice-' . $sale->invoice_no . '.pdf');
    }

    /* =========================================================
       DELETE IMPACT ANALYSIS
    ========================================================= */
    public function deleteImpact($id)
    {
        try {
            $sale = Sale::with(['payments', 'customer'])->findOrFail($id);

            $totalPaid = $sale->payments->sum('amount');
            $walletUsed = $sale->payments->where('remarks', 'ADVANCE_USED')->sum('amount');
            $directPayments = $sale->payments->whereIn('remarks', ['INVOICE', 'EMI_DOWN'])->sum('amount');
            $advancePayments = $sale->payments->whereIn('remarks', ['EXCESS_TO_ADVANCE', 'ADVANCE_ONLY', 'WALLET_ADD'])->sum('amount');

            // Find potentially affected invoices
            $affectedInvoices = $this->findAffectedInvoices($sale);

            // Calculate impact on balances
            $walletImpact = $advancePayments - $walletUsed;
            $openImpact = $directPayments + $walletUsed;

            return response()->json([
                'success' => true,
                'invoice_no' => $sale->invoice_no,
                'grand_total' => $sale->grand_total,
                'total_paid' => $totalPaid,
                'wallet_used' => $walletUsed,
                'direct_payments' => $directPayments,
                'advance_payments' => $advancePayments,
                'payment_count' => $sale->payments->count(),
                'affected_count' => count($affectedInvoices),
                'affected_invoices' => $affectedInvoices,
                'wallet_impact' => $walletImpact,
                'open_impact' => $openImpact,
                'warning' => $totalPaid > 0
                    ? "⚠️ This will affect:\n• Wallet: " . ($walletImpact > 0 ? "+" : "") . "₹{$walletImpact}\n• Open Balance: +₹{$openImpact}"
                    : '✅ No payments to convert',
                'can_delete' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Delete Impact Error: ' . $e->getMessage(), [
                'sale_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error analyzing impact: ' . $e->getMessage()
            ], 500);
        }
    }
}