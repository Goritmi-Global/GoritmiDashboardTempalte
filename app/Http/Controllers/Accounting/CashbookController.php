<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Cashbook;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CashbookController extends Controller
{
    public function index()
    {
        $cashbook = Cashbook::firstOrCreate([
            'id' => 1,
        ], [
            'name' => 'Cash in Hand',
            'balance' => 0,
        ]);
 
        return Inertia::render('Accounting/Cashbook/Index', [
            'cashbook' => $cashbook,
        ]);
    }

    public function create()
    {
        return Inertia::render('Accounting/Cashbook/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        Cashbook::create($request->only('name', 'balance'));

        return redirect()->route('cashbook.index')->with('success', 'Cashbook created successfully.');
    }

    public function show(Cashbook $cashbook)
    {
        return Inertia::render('Accounting/Cashbook/Show', [
            'cashbook' => $cashbook,
        ]);
    }

    public function edit(Cashbook $cashbook)
    {
        return Inertia::render('Accounting/Cashbook/Edit', [
            'cashbook' => $cashbook,
        ]);
    }

    public function update(Request $request, Cashbook $cashbook)
    {
        // dd("test");
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        $cashbook->update($request->only('name', 'balance'));

        return redirect()->back()->with('success', 'Cashbook updated successfully.');
    }

    public function destroy(Cashbook $cashbook)
    {
        try {
            $cashbook->delete();
            return redirect()->route('cashbook.index')->with('success', 'Cashbook deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete cashbook.');
        }
    }
}