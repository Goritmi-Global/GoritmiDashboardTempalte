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

        ExpenseType::create($request->only('name'));

        return redirect()->back()->with('success', 'Expense type created successfully.');
    }

    public function update(Request $request, ExpenseType $expenseType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_types,name,' . $expenseType->id,
        ]);

        $expenseType->update($request->only('name'));

        return redirect()->back()->with('success', 'Expense type updated successfully.');
    }

    public function destroy(ExpenseType $expenseType)
    {
        $expenseType->delete();

        return redirect()->back()->with('success', 'Expense type deleted successfully.');
    }
}
