<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\ExpenseType;
use Illuminate\Http\Request;
use Inertia\Inertia;

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



}
