<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Bank;
use App\Models\Accounting\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::latest()->get();

        return Inertia::render('Accounting/Banks/Index', [
            'banks' => $banks,
        ]);
    }

    public function store(Request $request)
    { 
        $request->validate([
            'name' => 'required|string|max:255|unique:banks,name',
            'account_number' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
        ]);

      DB::transaction(function () use ($request) {
    $bank = Bank::create($request->only('name', 'account_number', 'branch', 'balance'));

    if ($request->balance > 0) {
        Account::create([
            'type' => 'opening_balance',
            'amount' => $request->balance,
            'description' => 'Initial Bank Balance',
            'date' => now(),
            'user_id' => auth()->id(),
            'account_country' => 'PK', // or dynamic
            'bank_id' => $bank->id,
            'sourceable_type' => \App\Models\Accounting\Bank::class,
            'sourceable_id' => $bank->id,
        ]);
    }
});


        return redirect()->back()->with('success', 'Bank created successfully.');
    }

    public function update(Request $request, Bank $bank)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:banks,name,' . $bank->id,
        'account_number' => 'required|string|max:255',
        'branch' => 'nullable|string|max:255',
        'balance' => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($request, $bank) {
        $previousBalance = $bank->balance;
 
        $bank->update($request->only('name', 'account_number', 'branch', 'balance'));
 
             
            $existing_record = Account::where('sourceable_type', Bank::class)
                ->where('bank_id', $bank->id)
                ->where('type', 'opening_balance')
                ->first();
 
            if ($existing_record) {
              
                $existing_record->update([
                    'amount' => $request->balance,
                    'description' => 'Updated Bank Balance From ' . $previousBalance . ' to ' . $request->balance,
                    'date' => now(),
                    'user_id' => auth()->id(),
                ]);
            } else {
          
                Account::create([
                    'type' => 'opening_balance',
                    'amount' => $request->balance,
                    'description' => 'Initial Bank Balance set to ' . $request->balance,
                    'date' => now(),
                    'user_id' => auth()->id(),
                    'account_country' => 'PK', // or use $bank->country if available
                    'bank_id' => $bank->id,
                    'sourceable_type' => Bank::class,
                    'sourceable_id' => $bank->id,
                ]);
            }
    
        
    });

    return redirect()->back()->with('success', 'Bank updated successfully.');
}


    public function destroy(Bank $bank)
    {
        try {
            $bank->delete();
            return response()->json(['message' => 'Bank deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while deleting.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
