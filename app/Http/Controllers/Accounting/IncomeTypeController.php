<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\IncomeType;
use Illuminate\Http\Request;
use Inertia\Inertia;
 
use App\Models\Accounting\Income;
use App\Models\Accounting\Account;

class IncomeTypeController extends Controller
{
    public function index()
    {
        $types = IncomeType::latest()->get();

        return Inertia::render('Accounting/IncomeTypes/Index', [
            'types' => $types
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:income_types,name',
            'description' => 'nullable|string|max:1000',
        ]);

        IncomeType::create($request->only('name', 'description'));

        return redirect()->back();
    }

    public function update(Request $request, IncomeType $incomeType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:income_types,name,' . $incomeType->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $incomeType->update($request->only('name', 'description'));

        return redirect()->back();
    }

    public function destroy(IncomeType $incomeType)
    {
        try {
            if ($incomeType->incomes()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete. This income type is associated with existing records.'
                ], 422);
            }

            $incomeType->delete();

            return response()->json(['message' => 'Income type deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong while deleting.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function details($id)
{
    $type = IncomeType::findOrFail($id);

    // Step 1: Fetch all incomes of this type
    $incomes = Income::where('income_type_id', $id)->get();

    // Step 2: Get account IDs from those incomes
    $accountIds = $incomes->pluck('account_id')->unique();

    // Step 3: Fetch related accounts
    $accounts = Account::with('sourceable')
        ->whereIn('id', $accountIds)
        ->get()
        ->keyBy('id');

    // Step 4: Attach account to each income
    $incomesWithAccounts = $incomes->map(function ($income) use ($accounts) {
        $income->account = $accounts[$income->account_id] ?? null;
        return $income;
    });

    // Step 5: Totals
    $total = $incomesWithAccounts->sum(fn($i) => $i->account?->amount ?? 0);

    return Inertia::render('Accounting/IncomeTypes/Details', [
        'type' => $type,
        'incomes' => $incomesWithAccounts,
        'totalAmount' => $total,
        'incomeCount' => $incomesWithAccounts->count(),
    ]);
}

}
