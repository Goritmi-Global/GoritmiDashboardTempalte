<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\{Income, Expense, Account, Bank, Cashbook,ExpenseType,IncomeType, ForeCasting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Upload;

class TransactionController extends Controller
{
    public function index()
{ 
    $accounts = Account::with([
        'sourceable',
        'incomes.upload',
        'expenses.upload'
    ])
    ->latest()
    ->paginate(20);

    // Append image URL if exists
    $accounts->getCollection()->transform(function ($account) {
        $imageId = null;

        if ($account->type === 'income' && $account->incomes->first()?->receipt_image) {
            $imageId = $account->incomes->first()->receipt_image;
        } elseif ($account->type === 'expense' && $account->expenses->first()?->receipt_image) {
            $imageId = $account->expenses->first()->receipt_image;
        }

        $account->receipt_image_url = $imageId ? get_upload_url($imageId) : null;
        return $account;
    });

    return inertia('Accounting/Transactions/Index', [
        'accounts' => $accounts,
        'banks' => Bank::select('id', 'name')->get(),
        'expenseTypes' => ExpenseType::select('id', 'name')->get(),
        'incomeTypes' => IncomeType::select('id', 'name')->get(),
    ]);
}


public function edit($id)
    {
        $transaction = Account::with(['sourceable', 'income', 'expense'])->findOrFail($id);

        return inertia('Accounting/Transactions/Form', [
            'transaction' => $transaction,
            'banks' => Bank::select('id', 'name')->get()->toArray(),
            'cashbook' => Cashbook::first(),
            'incomeTypes' => IncomeType::select('id', 'name')->get()->toArray(),
            'expenseTypes' => ExpenseType::select('id', 'name')->get()->toArray(),
        ]);
    }
     public function store(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|in:income,expense,bank_to_cash,cash_to_bank',
        'account_type' => 'required|in:bank,cash',
        'amount' => 'required|numeric|min:0.01',
        'reference' => 'nullable|string',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'txn_type_id' => 'nullable|integer',
        'source_id' => 'nullable|integer',
        'destination_bank_id' => 'nullable|integer',
        'receipt_no' => 'nullable|string',
        'cropped_image' => 'nullable|string',
        'acount_country' => 'required|string|max:10',
    ]);

    DB::transaction(function () use ($validated) {
        $userId = auth()->id();
        $uploadId = null;

        // Upload receipt image (if available)
        if (!empty($validated['cropped_image'])) {
            $uploadId = store_base64_image($validated['cropped_image']);
        }

        // Handle INCOME or EXPENSE
        if (in_array($validated['type'], ['income', 'expense'])) {
            $account = new Account([
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'reference' => $validated['reference'],
                'description' => $validated['description'],
                'date' => $validated['date'],
                'user_id' => $userId,
                'account_country' => $validated['acount_country'],
            ]);

            if ($validated['account_type'] === 'bank') {
                $bank = Bank::findOrFail($validated['source_id']);
                $account->sourceable()->associate($bank);
                $account->bank_id = $bank->id;
                $bank->balance += $validated['type'] === 'income' ? $validated['amount'] : -$validated['amount'];
                $bank->save();
            } else {
                $cashbook = Cashbook::first();
                $account->sourceable()->associate($cashbook);
                $account->cashbook_id = $cashbook->id;
                $cashbook->balance += $validated['type'] === 'income' ? $validated['amount'] : -$validated['amount'];
                $cashbook->save();
            }

            $account->save();

            if ($validated['type'] === 'income') {
                Income::create([
                    'income_type_id' => $validated['txn_type_id'],
                    'account_id' => $account->id,
                    'receipt_no' => $validated['receipt_no'],
                    'receipt_image' => $uploadId,
                ]);
            } else {
                Expense::create([
                    'expense_type_id' => $validated['txn_type_id'],
                    'account_id' => $account->id,
                    'receipt_no' => $validated['receipt_no'],
                    'receipt_image' => $uploadId,
                ]);
            }
        }

        // Handle BANK TO CASH transfer
        elseif ($validated['type'] === 'bank_to_cash') {
            $bank = Bank::findOrFail($validated['source_id']);
            $cashbook = Cashbook::first();

            $account = new Account([
                'type' => 'bank_to_cash',
                'amount' => $validated['amount'],
                'reference' => $validated['reference'],
                'description' => $validated['description'] ?? 'Transferred from Bank to Cashbook',
                'date' => $validated['date'],
                'user_id' => $userId,
                'account_country' => $validated['acount_country'],
                'bank_id' => $bank->id,
                'cashbook_id' => $cashbook->id,
            ]);

            $account->sourceable()->associate($bank);
            $account->save();

            $bank->balance -= $validated['amount'];
            $bank->save();

            $cashbook->balance += $validated['amount'];
            $cashbook->save();
        }

        // Handle CASH TO BANK transfer
        elseif ($validated['type'] === 'cash_to_bank') {
            $bank = Bank::findOrFail($validated['destination_bank_id']);
            $cashbook = Cashbook::first();

            $account = new Account([
                'type' => 'cash_to_bank',
                'amount' => $validated['amount'],
                'reference' => $validated['reference'],
                'description' => $validated['description'] ?? 'Transferred from Cashbook to Bank',
                'date' => $validated['date'],
                'user_id' => $userId,
                'account_country' => $validated['acount_country'],
                'bank_id' => $bank->id,
                'cashbook_id' => $cashbook->id,
            ]);

            $account->sourceable()->associate($cashbook);
            $account->save();

            $cashbook->balance -= $validated['amount'];
            $cashbook->save();

            $bank->balance += $validated['amount'];
            $bank->save();
        }
    });

    return redirect()->back()->with('success', 'Transaction created successfully.');
}






        
}
