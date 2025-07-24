<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Cashbook;
use App\Models\Accounting\Account;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;
class CashbookController extends Controller
{
    public function index()
    {
        // $cashbook = Cashbook::firstOrCreate([
        //     'id' => 1,
        // ], [
        //     'name' => 'Cash in Hand',
        //     'balance' => 0,
        // ]);
 
        
      
        return Inertia::render('Accounting/Cashbook/Index', [
            'cashbook' => Cashbook::first(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Accounting/Cashbook/Create');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'balance' => 'required|numeric|min:0',
    //     ]);

    //     Cashbook::create($request->only('name', 'balance'));

    //     return redirect()->route('cashbook.index')->with('success', 'Cashbook created successfully.');
    // }

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
    $request->validate([
        'name' => 'required|string|max:255|unique:cashbooks,name,' . $cashbook->id,
        'balance' => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($request, $cashbook) {
         
        $previousBalance = $cashbook->balance;

        $cashbook->update($request->only('name', 'balance'));

        $existing_record = Account::where('sourceable_type', Cashbook::class)
            ->where('sourceable_id', $cashbook->id)
            ->where('type', 'opening_balance')
            ->first();

        if ($existing_record) {
            $existing_record->update([
                'amount' => $request->balance,
                'description' => 'Updated Cashbook Balance From ' . $previousBalance . ' to ' . $request->balance,
                'date' => now(),
                'user_id' => auth()->id(),
            ]);
        } else {
            Account::create([
                'type' => 'opening_balance',
                'amount' => $request->balance,
                'description' => 'Initial Cashbook Balance set to ' . $request->balance,
                'date' => now(),
                'user_id' => auth()->id(),
                'account_country' => 'PK', // or dynamic if needed
                'cashbook_id' => $cashbook->id,
                'sourceable_type' => Cashbook::class,
                'sourceable_id' => $cashbook->id,
            ]);
        }
    });

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