<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /* ===============================
       CUSTOMER LIST (ADMIN PAGE)
    =============================== */
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    /* ===============================
       AJAX LIVE SEARCH (SALES PAGE)
       For large data (1000+)
    =============================== */
    public function ajaxSearch(Request $request)
    {
        $search = trim($request->search);

        // 🔒 Small protection (performance)
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('gst_no', 'like', "%{$search}%");
            })
            ->latest()
            ->limit(20)
            ->get([
                'id',
                'name',
                'mobile',
                'email',
                'gst_no',
                'address'
            ]);

        return response()->json($customers);
    }

    /* ===============================
       CREATE CUSTOMER PAGE
    =============================== */
    public function create()
    {
        return view('customers.create');
    }

    /* ===============================
       STORE CUSTOMER (NORMAL FORM)
    =============================== */
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:customers,mobile',
            'email'  => 'nullable|email|unique:customers,email',
            'gst_no' => 'nullable|string|max:20',
            'address'=> 'nullable|string',
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

    /* ===============================
       EDIT CUSTOMER
    =============================== */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /* ===============================
       UPDATE CUSTOMER
    =============================== */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:customers,mobile,' . $customer->id,
            'email'  => 'nullable|email|unique:customers,email,' . $customer->id,
            'gst_no' => 'nullable|string|max:20',
            'address'=> 'nullable|string',
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
            ->with('success', 'Customer updated successfully');
    }

    /* ===============================
       DELETE CUSTOMER
    =============================== */
    public function destroy($id)
    {
        Customer::destroy($id);
        return back()->with('success', 'Customer deleted successfully');
    }

    /* ===============================
       AJAX STORE (FROM SALES MODAL)
    =============================== */
    public function storeAjax(Request $request)
    {
        try {
            $request->validate([
                'name'    => 'required|string|max:255',
                'mobile'  => 'required|string|max:20',
                'email'   => 'nullable|email',
                'address' => 'nullable|string',
            ]);

            // 🔍 Prevent duplicate by mobile
            $existing = Customer::where('mobile', $request->mobile)->first();
            if ($existing) {
                return response()->json([
                    'success'  => true,
                    'customer' => $existing,
                    'message'  => 'Existing customer selected'
                ]);
            }

            $customer = Customer::create([
                'name'    => trim($request->name),
                'mobile'  => trim($request->mobile),
                'email'   => trim($request->email),
                'address' => trim($request->address),
            ]);

            return response()->json([
                'success'  => true,
                'customer' => $customer,
                'message'  => 'Customer created successfully'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /* ===============================
       CUSTOMER SALES (VIEW BUTTON)
    =============================== */
    public function sales(Customer $customer)
    {
        $sales = $customer->sales()
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('customers.sales', compact('customer', 'sales'));
    }
}
