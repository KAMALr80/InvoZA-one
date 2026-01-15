<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /* ================= LIST ================= */
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    /* ================= CREATE ================= */
    public function create()
    {
        return view('customers.create');
    }

public function store(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:255',
        'mobile' => 'required|string|max:15|unique:customers,mobile',
        'email'  => 'nullable|email|unique:customers,email',
    ]);

    Customer::create([
        'name'    => trim($request->name),
        'mobile'  => trim($request->mobile),
        'email'   => trim($request->email),
        'gst_no'  => trim($request->gst_no),
        'address' => trim($request->address),
    ]);

    return redirect()
        ->route('customers.index')
        ->with('success', 'Customer added successfully');
}

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

  public function update(Request $request, $id)
{
    $customer = Customer::findOrFail($id);

    $request->validate([
        'name'   => 'required|string|max:255',
        'mobile' => 'required|string|max:15|unique:customers,mobile,' . $customer->id,
        'email'  => 'nullable|email|unique:customers,email,' . $customer->id,
    ]);

    $customer->update([
        'name'    => trim($request->name),
        'mobile'  => trim($request->mobile),
        'email'   => trim($request->email),
        'gst_no'  => trim($request->gst_no),
        'address' => trim($request->address),
    ]);

    return redirect()
        ->route('customers.index')
        ->with('success', 'Customer updated');
}


    /* ================= DELETE ================= */
    public function destroy($id)
    {
        Customer::destroy($id);
        return back()->with('success', 'Customer deleted');
    }

    /* ================= AJAX STORE (FROM SALES PAGE) ================= */
    public function ajaxStore(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'mobile' => 'required',
        ]);

        $customer = Customer::create([
            'name'    => $request->name,
            'mobile'  => $request->mobile,
            'email'   => $request->email,
            'address' => $request->address,
            'gst_no'  => $request->gst_no,
        ]);

        return response()->json([
            'success'  => true,
            'customer' => $customer,
        ]);
    }

    /* ================= CUSTOMER SALES (👁 VIEW BUTTON) ================= */
    public function sales(Customer $customer)
    {
        $sales = $customer->sales()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('customers.sales', compact('customer', 'sales'));
    }
}
