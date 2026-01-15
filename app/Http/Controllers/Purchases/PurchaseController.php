<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /* =========================
       PURCHASE LIST
    ==========================*/
 public function index()
{
    $purchases = Purchase::with('product')
        ->latest()
        ->paginate(10);

    $totalProducts = Product::count();

    $totalPurchaseAmount = Purchase::sum(
        DB::raw('quantity * price')
    );

    $lowStockProducts = Product::where('quantity', '<=', 5)->get();

    return view('purchases.index', compact(
        'purchases',
        'totalProducts',
        'totalPurchaseAmount',
        'lowStockProducts'
    ));
}

    /* =========================
       CREATE PURCHASE PAGE
    ==========================*/
    public function create()
    {
        $products = Product::all();
        return view('purchases.create', compact('products'));
    }

    /* =========================
       STORE PURCHASE
    ==========================*/
    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {

            $product = Product::lockForUpdate()->findOrFail($request->product_id);

            Purchase::create([
                'product_id'    => $request->product_id,
                'quantity'      => $request->quantity,
                'price'         => $request->price,
                'total'         => $request->quantity * $request->price,
                'purchase_date' => $request->purchase_date,
            ]);

            // ✅ Increase stock
            $product->increment('quantity', $request->quantity);
        });

        return redirect()
            ->route('purchases.index')
            ->with('success', 'Purchase added & stock updated');
    }

    /* =========================
       EDIT PURCHASE
    ==========================*/
    public function edit(Purchase $purchase)
    {
        $products = Product::all();
        return view('purchases.edit', compact('purchase', 'products'));
    }

    /* =========================
       UPDATE PURCHASE
    ==========================*/
   public function update(Request $request, Purchase $purchase)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'price'    => 'required|numeric|min:0',
        'purchase_date' => 'required|date',
    ]);

    DB::transaction(function () use ($request, $purchase) {

        $product = Product::lockForUpdate()->findOrFail($purchase->product_id);

        // 🔁 revert old stock
        $product->decrement('quantity', $purchase->quantity);

        // 🔼 add new stock
        $product->increment('quantity', $request->quantity);

        // 🔄 update purchase
        $purchase->update([
            'quantity'      => $request->quantity,
            'price'         => $request->price,
            'total'         => $request->quantity * $request->price,
            'purchase_date' => $request->purchase_date,
        ]);
    });

    return redirect()
        ->route('purchases.index')
        ->with('success', 'Purchase updated & stock adjusted');
}


    /* =========================
       DELETE PURCHASE
    ==========================*/
    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            $purchase->product->increment('quantity', $purchase->quantity);
            $purchase->delete();
        });

        return back()->with('success', 'Purchase deleted & stock restored');
    }
}
