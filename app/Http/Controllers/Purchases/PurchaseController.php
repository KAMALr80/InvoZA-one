<?php

namespace App\Http\Controllers\Purchases;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function index()
    {
$purchases = Purchase::with('product')
    ->latest()
    ->paginate(10);

$products = Product::all();

return view('purchases.index', compact('purchases', 'products'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required',
            'quantity'      => 'required|integer|min:1',
            'price'         => 'required|numeric|min:0',
            'purchase_date' => 'required|date'
        ]);

        $total = $request->quantity * $request->price;

        Purchase::create([
            'product_id'    => $request->product_id,
            'quantity'      => $request->quantity,
            'price'         => $request->price,
            'total'         => $total,
            'purchase_date' => $request->purchase_date
        ]);

        // increase stock
        Product::where('id', $request->product_id)
            ->increment('quantity', $request->quantity);

        return redirect('/purchases')->with('success', 'Purchase recorded & stock updated');
    }
}
