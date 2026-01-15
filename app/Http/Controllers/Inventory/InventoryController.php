<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class InventoryController extends Controller
{
   public function index(Request $request)
{
    $search = $request->search;

    $products = Product::when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('product_code', 'like', "%$search%")
              ->orWhere('category', 'like', "%$search%");
        })
        ->latest()
        ->paginate(15)
        ->withQueryString(); // search pagination ke sath maintain rahe

    return view('inventory.index', compact('products'));
}


    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'quantity' => 'required|integer',
            'price'    => 'required|numeric'
        ]);

        $code = 'PRD' . str_pad(Product::count() + 1, 4, '0', STR_PAD_LEFT);

        Product::create([
            'product_code' => $code,
            'name'         => $request->name,
            'quantity'     => $request->quantity,
            'price'        => $request->price,
            'category'     => $request->category
        ]);

        return redirect('/inventory')->with('success', 'Product added');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('inventory.edit', compact('product'));
    }

   public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $product->update([
        'name'     => $request->name,
        'price'    => $request->price,
        'category' => $request->category,
    ]);

    return redirect('/inventory')->with('success', 'Product updated');
}

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect('/inventory')->with('success', 'Product deleted');
    }
}
