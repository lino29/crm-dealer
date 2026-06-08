<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function index()
    {
        $dealers = Dealer::latest()->paginate(10);
        return view('admin.dealers.index', compact('dealers'));
    }

    public function create()
    {
        return view('admin.dealers.create');
    }

    public function store(\App\Http\Requests\StoreDealerRequest $request)
    {
        Dealer::create($request->validated());
        return redirect()->route('admin.dealers.index')->with('success', 'Dealer created successfully.');
    }

    public function show(Dealer $dealer)
    {
        return view('admin.dealers.show', compact('dealer'));
    }

    public function edit(Dealer $dealer)
    {
        return view('admin.dealers.edit', compact('dealer'));
    }

    public function update(\App\Http\Requests\UpdateDealerRequest $request, Dealer $dealer)
    {
        $dealer->update($request->validated());
        return redirect()->route('admin.dealers.index')->with('success', 'Dealer updated successfully.');
    }

    public function destroy(Dealer $dealer)
    {
        $dealer->update(['status' => 'inactive']);
        return redirect()->route('admin.dealers.index')->with('success', 'Dealer set to inactive.');
    }
}
