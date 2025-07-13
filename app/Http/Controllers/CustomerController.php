<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        if ($request->filled('national_code')) {
            $query->where('national_code', 'like', '%' . $request->national_code . '%');
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }
        if ($request->filled('loyal')) {
            if ($request->loyal == '1') {
                $query->where('total_orders', '>=', 5)->where('total_purchases', '>=', 1000000);
            } elseif ($request->loyal == '0') {
                $query->where(function($q){
                    $q->where('total_orders', '<', 5)->orWhere('total_purchases', '<', 1000000);
                });
            }
        }

        $customers = $query->latest()->paginate(10)->appends($request->query());
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'is_active' => 'boolean'
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'مشتری با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id . '|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'مشتری با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'مشتری با موفقیت حذف شد.');
    }
}
