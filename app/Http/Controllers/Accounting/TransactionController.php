<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\{Income, Expense, Account, Bank, Cashbook,ExpenseType,IncomeType};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
{ 
    $accounts = Account::with('sourceable')->latest()->paginate(20);
    $banks = Bank::select('id', 'name')->get();
    $expenseTypes = ExpenseType::select('id', 'name')->get();
    $incomeTypes = IncomeType::select('id', 'name')->get();

   
    return inertia('Accounting/Transactions/Index', [
        'accounts' => $accounts,
        'banks' => $banks,
        'expenseTypes' => $expenseTypes,
        'incomeTypes' => $incomeTypes,
    ]);
}

    public function create()
    {
        return inertia('Accounting/Transactions/Form', [
            'banks' => Bank::all(),
            'cashbook' => Cashbook::first(),
            'incomeTypes' => \App\Models\Accounting\IncomeType::all(),
            'expenseTypes' => \App\Models\Accounting\ExpenseType::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'account_source' => 'required|in:bank,cash',
            'source_id' => 'required|integer',
            'category_id' => 'required|integer',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {
            $account = new Account([
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'reference' => null,
                'description' => $validated['description'],
                'user_id' => auth()->id(),
            ]);

            if ($validated['account_source'] === 'bank') {
                $bank = Bank::findOrFail($validated['source_id']);
                $account->sourceable()->associate($bank);
                $bank->update(['balance' => $bank->balance + ($validated['type'] === 'income' ? $validated['amount'] : -$validated['amount'])]);
            } else {
                $cashbook = Cashbook::findOrFail($validated['source_id']);
                $account->sourceable()->associate($cashbook);
                $cashbook->update(['balance' => $cashbook->balance + ($validated['type'] === 'income' ? $validated['amount'] : -$validated['amount'])]);
            }

            $account->save();

            if ($validated['type'] === 'income') {
                Income::create([
                    'income_type_id' => $validated['category_id'],
                    'account_id' => $account->id,
                ]);
            } else {
                Expense::create([
                    'expense_type_id' => $validated['category_id'],
                    'account_id' => $account->id,
                ]);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaction saved successfully.');
    }
}
