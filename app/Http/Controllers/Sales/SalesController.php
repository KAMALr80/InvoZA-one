<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;

class SalesController extends Controller
{
    public function index(Request $request)
{
    $sales = Sale::with('product')
        ->latest()
        ->paginate(10);

    $products = Product::all();

    return view('sales.index', compact('sales', 'products'));
}


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity'   => 'required|integer|min:1',
            'sale_date'  => 'required|date'
        ]);

        $product = Product::findOrFail($request->product_id);

        // stock check
        if ($request->quantity > $product->quantity) {
            return back()->with('error', 'Not enough stock');
        }

        $total = $request->quantity * $product->price;

        Sale::create([
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
            'price'      => $product->price,
            'total'      => $total,
            'sale_date'  => $request->sale_date
        ]);

        // reduce stock
        $product->decrement('quantity', $request->quantity);

        return redirect('/sales')->with('success', 'Sale recorded');
    }
}
