<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = \App\Models\Customer::with('dealer')->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $dealers = \App\Models\Dealer::where('status', 'active')->get();
        return view('admin.customers.create', compact('dealers'));
    }

    public function store(\App\Http\Requests\StoreCustomerRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        \App\Models\Customer::create($data);
        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(\App\Models\Customer $customer)
    {
        $customer->load(['dealer', 'creator']);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(\App\Models\Customer $customer)
    {
        $dealers = \App\Models\Dealer::where('status', 'active')->get();
        return view('admin.customers.edit', compact('customer', 'dealers'));
    }

    public function update(\App\Http\Requests\UpdateCustomerRequest $request, \App\Models\Customer $customer)
    {
        $customer->update($request->validated());
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(\App\Models\Customer $customer)
    {
        $customer->update(['status' => 'inactive']);
        return redirect()->route('admin.customers.index')->with('success', 'Customer set to inactive.');
    }
}
