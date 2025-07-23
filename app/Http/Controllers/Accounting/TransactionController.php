<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\{Income, Expense, Account, Bank, Cashbook,ExpenseType,IncomeType, ForeCasting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Upload;
use Illuminate\Support\Facades\Log;


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


// public function edit($id)
//     {
//         $transaction = Account::with(['sourceable', 'income', 'expense'])->findOrFail($id);

//         return inertia('Accounting/Transactions/Form', [
//             'transaction' => $transaction,
//             'banks' => Bank::select('id', 'name')->get()->toArray(),
//             'cashbook' => Cashbook::first(),
//             'incomeTypes' => IncomeType::select('id', 'name')->get()->toArray(),
//             'expenseTypes' => ExpenseType::select('id', 'name')->get()->toArray(),
//         ]);
//     }
    
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
        'account_country' => 'required|string|max:10',
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
                'account_country' => $validated['account_country'],
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
                'account_country' => $validated['account_country'],
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
                'account_country' => $validated['account_country'],
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


public function destroy(Account $transaction)
{
    DB::transaction(function () use ($transaction) {
        $amount = $transaction->amount;
        $type = $transaction->type;

        // Reverse bank or cashbook balances
        if ($type === 'income' || $type === 'expense') {
            if ($transaction->bank_id && $transaction->sourceable_type === Bank::class) {
                $bank = Bank::find($transaction->bank_id);
                if ($bank) {
                    $bank->balance -= $type === 'income' ? $amount : -$amount;
                    $bank->save();
                }
            }

            if ($transaction->cashbook_id && $transaction->sourceable_type === Cashbook::class) {
                $cashbook = Cashbook::find($transaction->cashbook_id);
                if ($cashbook) {
                    $cashbook->balance -= $type === 'income' ? $amount : -$amount;
                    $cashbook->save();
                }
            }

            // Delete linked income/expense
            if ($type === 'income') {
                $transaction->incomes()->delete();
            } elseif ($type === 'expense') {
                $transaction->expenses()->delete();
            }
        }

        // Reverse bank_to_cash
        elseif ($type === 'bank_to_cash') {
            $bank = Bank::find($transaction->bank_id);
            $cashbook = Cashbook::find($transaction->cashbook_id);
            if ($bank) {
                $bank->balance += $amount;
                $bank->save();
            }
            if ($cashbook) {
                $cashbook->balance -= $amount;
                $cashbook->save();
            }
        }

        // Reverse cash_to_bank
        elseif ($type === 'cash_to_bank') {
            $bank = Bank::find($transaction->bank_id);
            $cashbook = Cashbook::find($transaction->cashbook_id);
            if ($cashbook) {
                $cashbook->balance += $amount;
                $cashbook->save();
            }
            if ($bank) {
                $bank->balance -= $amount;
                $bank->save();
            }
        }

        // Delete receipt file if uploaded
        if ($transaction->incomes->first()?->receipt_image || $transaction->expenses->first()?->receipt_image) {
            $uploadId = $transaction->incomes->first()?->receipt_image ?? $transaction->expenses->first()?->receipt_image;
            $upload = Upload::find($uploadId);
            if ($upload) {
                delete_file($upload->file_name); // Your helper to remove file
                $upload->delete();
            }
        }

        // Finally delete the account record
        $transaction->delete();
    });

    return response()->json(['message' => 'Transaction deleted successfully.']);
}



public function update(Request $request, Account $transaction)
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
        'account_country' => 'required|string|max:10',
    ]);

    DB::transaction(function () use ($validated, $transaction) {
        $oldType = $transaction->type;
        $oldAmount = $transaction->amount;

        $typeChanged = $oldType !== $validated['type'];
        $amountChanged = $oldAmount != $validated['amount'];

        // Only reverse if type or amount changed
        if ($typeChanged || $amountChanged) {
            if (in_array($oldType, ['income', 'expense'])) {
                if ($transaction->bank_id && $transaction->sourceable_type === Bank::class) {
                    $bank = Bank::find($transaction->bank_id);
                    if ($bank) {
                        $bank->balance -= ($oldType === 'income') ? $oldAmount : -$oldAmount;
                        $bank->save();
                    }
                } elseif ($transaction->cashbook_id && $transaction->sourceable_type === Cashbook::class) {
                    $cashbook = Cashbook::find($transaction->cashbook_id);
                    if ($cashbook) {
                        $cashbook->balance -= ($oldType === 'income') ? $oldAmount : -$oldAmount;
                        $cashbook->save();
                    }
                }
            } elseif ($oldType === 'bank_to_cash') {
                $bank = Bank::find($transaction->bank_id);
                $cashbook = Cashbook::find($transaction->cashbook_id);
                if ($bank) $bank->balance += $oldAmount;
                if ($cashbook) $cashbook->balance -= $oldAmount;
                if ($bank) $bank->save();
                if ($cashbook) $cashbook->save();
            } elseif ($oldType === 'cash_to_bank') {
                $bank = Bank::find($transaction->bank_id);
                $cashbook = Cashbook::find($transaction->cashbook_id);
                if ($bank) $bank->balance -= $oldAmount;
                if ($cashbook) $cashbook->balance += $oldAmount;
                if ($bank) $bank->save();
                if ($cashbook) $cashbook->save();
            }

            // Remove old type-specific records
            $transaction->incomes()->delete();
            $transaction->expenses()->delete();
        }

        // Upload image
        $uploadId = null;
        if (!empty($validated['cropped_image'])) {
            $uploadId = store_base64_image($validated['cropped_image']);
        }

        // Fill base data
        $transaction->fill([
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'reference' => $validated['reference'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'account_country' => $validated['account_country'],
        ]);

        // --- Handle Modes ---
        if (in_array($validated['type'], ['income', 'expense'])) {
            if ($validated['account_type'] === 'bank') {
                $bank = Bank::findOrFail($validated['source_id']);
                $transaction->sourceable()->associate($bank);
                $transaction->bank_id = $bank->id;
                $transaction->cashbook_id = null;

                $bank->balance += ($validated['type'] === 'income') ? $validated['amount'] : -$validated['amount'];
                $bank->save();
            } else {
                $cashbook = Cashbook::first();
                $transaction->sourceable()->associate($cashbook);
                $transaction->cashbook_id = $cashbook->id;
                $transaction->bank_id = null;

                $cashbook->balance += ($validated['type'] === 'income') ? $validated['amount'] : -$validated['amount'];
                $cashbook->save();
            }

            $transaction->save();

            if ($validated['type'] === 'income') {
                Income::create([
                    'income_type_id' => $validated['txn_type_id'],
                    'account_id' => $transaction->id,
                    'receipt_no' => $validated['receipt_no'],
                    'receipt_image' => $uploadId,
                ]);
            } else {
                Expense::create([
                    'expense_type_id' => $validated['txn_type_id'],
                    'account_id' => $transaction->id,
                    'receipt_no' => $validated['receipt_no'],
                    'receipt_image' => $uploadId,
                ]);
            }

        } elseif ($validated['type'] === 'bank_to_cash') {
            $bank = Bank::query()->lockForUpdate()->findOrFail($validated['source_id']);
            $cashbook = Cashbook::query()->lockForUpdate()->firstOrFail();

            $transaction->sourceable()->associate($bank);
            $transaction->bank_id = $bank->id;
            $transaction->cashbook_id = $cashbook->id;

            $bankBefore = $bank->balance;
            $cashBefore = $cashbook->balance;

            $bank->balance -= $validated['amount'];
            $cashbook->balance += $validated['amount'];

            $bank->save();
            $cashbook->save();
            $transaction->save();

            Log::info('Updated bank_to_cash transaction', [
                'transaction_id' => $transaction->id,
                'bank_id' => $bank->id,
                'cashbook_id' => $cashbook->id,
                'amount' => $validated['amount'],
                'bank_balance_before' => $bankBefore,
                'bank_balance_after' => $bank->balance,
                'cashbook_balance_before' => $cashBefore,
                'cashbook_balance_after' => $cashbook->balance,
            ]);

        } elseif ($validated['type'] === 'cash_to_bank') {
            $cashbook = Cashbook::query()->lockForUpdate()->firstOrFail();
            $bank = Bank::query()->lockForUpdate()->findOrFail($validated['destination_bank_id']);

            $transaction->sourceable()->associate($cashbook);
            $transaction->cashbook_id = $cashbook->id;
            $transaction->bank_id = $bank->id;

            $bankBefore = $bank->balance;
            $cashBefore = $cashbook->balance;

            $cashbook->balance -= $validated['amount'];
            $bank->balance += $validated['amount'];

            $cashbook->save();
            $bank->save();
            $transaction->save();

            Log::info('Updated cash_to_bank transaction', [
                'transaction_id' => $transaction->id,
                'bank_id' => $bank->id,
                'cashbook_id' => $cashbook->id,
                'amount' => $validated['amount'],
                'bank_balance_before' => $bankBefore,
                'bank_balance_after' => $bank->balance,
                'cashbook_balance_before' => $cashBefore,
                'cashbook_balance_after' => $cashbook->balance,
            ]);
        }
    });

    return redirect()->back()->with('success', 'Transaction updated successfully.');
}

}

 