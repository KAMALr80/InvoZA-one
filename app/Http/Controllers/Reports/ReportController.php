<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /* ======================================================
     | SALES REPORT
     ====================================================== */

    public function sales(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        $sales = Sale::with('product')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('sale_date', [$from, $to]);
            })
            ->get();

        return view('reports.sales', compact('sales', 'from', 'to'));
    }

    public function exportSalesCSV(Request $request)
    {
        $sales = $this->filteredSales($request);

        return $this->csvDownload(
            $sales,
            'sales_report.csv',
            ['Product', 'Quantity', 'Price', 'Total', 'Date'],
            fn ($s) => [
                $s->product->name,
                $s->quantity,
                $s->price,
                $s->total,
                $s->sale_date,
            ]
        );
    }

    public function exportSalesPDF(Request $request)
    {
        $sales = $this->filteredSales($request);

        return Pdf::loadView('reports.pdf.sales', compact('sales'))
            ->download('sales_report.pdf');
    }

    private function filteredSales(Request $request)
    {
        return Sale::with('product')
            ->when($request->from && $request->to, function ($q) use ($request) {
                $q->whereBetween('sale_date', [$request->from, $request->to]);
            })
            ->get();
    }

    /* ======================================================
     | PURCHASE REPORT
     ====================================================== */

    public function purchases(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        $purchases = Purchase::with('product')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('purchase_date', [$from, $to]);
            })
            ->get();

        return view('reports.purchases', compact('purchases', 'from', 'to'));
    }

    public function exportPurchasesCSV(Request $request)
    {
        $purchases = $this->filteredPurchases($request);

        return $this->csvDownload(
            $purchases,
            'purchase_report.csv',
            ['Product', 'Quantity', 'Price', 'Total', 'Date'],
            fn ($p) => [
                $p->product->name,
                $p->quantity,
                $p->price,
                $p->total,
                $p->purchase_date,
            ]
        );
    }

    public function exportPurchasesPDF(Request $request)
    {
        $purchases = $this->filteredPurchases($request);

        return Pdf::loadView('reports.pdf.purchases', compact('purchases'))
            ->download('purchase_report.pdf');
    }

    private function filteredPurchases(Request $request)
    {
        return Purchase::with('product')
            ->when($request->from && $request->to, function ($q) use ($request) {
                $q->whereBetween('purchase_date', [$request->from, $request->to]);
            })
            ->get();
    }

    /* ======================================================
     | ATTENDANCE REPORT
     ====================================================== */

   public function attendance(Request $request)
{
    $from = $request->from;
    $to   = $request->to;

    $attendance = Attendance::with('employee')
        ->when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('attendance_date', [$from, $to]);
        })
        ->orderBy('attendance_date', 'desc')
        ->get();

    return view('reports.attendance', compact('attendance','from','to'));
}


    public function exportAttendanceCSV(Request $request)
    {
        $attendance = $this->filteredAttendance($request);

        return $this->csvDownload(
            $attendance,
            'attendance_report.csv',
            ['Employee', 'Date', 'Status'],
            fn ($a) => [
                $a->employee->name,
                $a->attendance_date,
                $a->status,
            ]
        );
    }

    public function exportAttendancePDF(Request $request)
    {
        $attendance = $this->filteredAttendance($request);

        return Pdf::loadView('reports.pdf.attendance', compact('attendance'))
            ->download('attendance_report.pdf');
    }

    private function filteredAttendance(Request $request)
    {
        return Attendance::with('employee')
            ->when($request->from && $request->to, function ($q) use ($request) {
                $q->whereBetween('attendance_date', [$request->from, $request->to]);
            })
            ->get();
    }


    /* ======================================================
     | COMMON CSV HELPER
     ====================================================== */

    private function csvDownload($data, $filename, $headersRow, $rowCallback)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($data, $headersRow, $rowCallback) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headersRow);

            foreach ($data as $row) {
                fputcsv($file, $rowCallback($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
