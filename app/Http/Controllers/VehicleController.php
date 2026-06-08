<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        // Typically not used directly as vehicles are shown in customer detail
        $vehicles = \App\Models\Vehicle::with('customer')->latest()->paginate(10);
        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create(Request $request)
    {
        $customer_id = $request->query('customer_id');
        $customer = \App\Models\Customer::find($customer_id);
        $customers = \App\Models\Customer::where('status', 'active')->get();
        return view('admin.vehicles.create', compact('customer', 'customers'));
    }

    public function store(\App\Http\Requests\StoreVehicleRequest $request)
    {
        $vehicle = \App\Models\Vehicle::create($request->validated());
        return redirect()->route('admin.customers.show', $vehicle->customer_id)->with('success', 'Vehicle added successfully.');
    }

    public function show(\App\Models\Vehicle $vehicle)
    {
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(\App\Models\Vehicle $vehicle)
    {
        $customers = \App\Models\Customer::where('status', 'active')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'customers'));
    }

    public function update(\App\Http\Requests\UpdateVehicleRequest $request, \App\Models\Vehicle $vehicle)
    {
        $vehicle->update($request->validated());
        return redirect()->route('admin.customers.show', $vehicle->customer_id)->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(\App\Models\Vehicle $vehicle)
    {
        $vehicle->update(['status' => 'inactive']);
        return redirect()->route('admin.customers.show', $vehicle->customer_id)->with('success', 'Vehicle set to inactive.');
    }
}
