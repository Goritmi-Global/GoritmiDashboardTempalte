<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\ExpenseType;
use Illuminate\Http\Request;
use Inertia\Inertia; 
use App\Models\Accounting\Expense;
use App\Models\Accounting\Account;
 
class ExpenseTypeController extends Controller
{
    public function index()
    {
        $types = ExpenseType::latest()->get();

        return Inertia::render('Accounting/ExpenseTypes/Index', [
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_types,name',
        ]);

        ExpenseType::create($request->only('name','description'));

        return redirect()->back()->with('success', 'Expense type created successfully.');
    }

    public function update(Request $request, ExpenseType $expenseType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_types,name,' . $expenseType->id,
        ]);

        $expenseType->update($request->only('name','description'));

        return redirect()->back()->with('success', 'Expense type updated successfully.');
    }

    public function destroy(ExpenseType $expenseType)
    {
        try {
            // Check if the expense type is used in related tables
            if ($expenseType->expenses()->exists() || $expenseType->forecastings()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete. This expense type is associated with existing records.'
                ], 422);
            }

            $expenseType->delete();

            return response()->json(['message' => 'Expense type deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while deleting.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



public function details($id)
{
    $type = ExpenseType::findOrFail($id);

    // Step 1: Get all expenses of this type
    $expenses = Expense::where('expense_type_id', $id)->get();

    // Step 2: Get account IDs and fetch account data
    $accountIds = $expenses->pluck('account_id')->unique();
    $accounts = Account::with('sourceable')
        ->whereIn('id', $accountIds)
        ->get()
        ->keyBy('id');

    // Step 3: Append each expense with its account
    $expensesWithAccounts = $expenses->map(function ($expense) use ($accounts) {
        $expense->account = $accounts[$expense->account_id] ?? null;
        return $expense;
    });

    // Step 4: Compute totals
    $total = $expensesWithAccounts->sum(fn($e) => $e->account?->amount ?? 0);

    return Inertia::render('Accounting/ExpenseTypes/Details', [
        'type' => $type,
        'expenses' => $expensesWithAccounts,
        'totalAmount' => $total,
        'expenseCount' => $expensesWithAccounts->count(),
    ]);
}





}
