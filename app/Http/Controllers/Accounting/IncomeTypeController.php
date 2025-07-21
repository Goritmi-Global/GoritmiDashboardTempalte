<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\IncomeType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncomeTypeController extends Controller
{
    public function index()
    { 
        $types = IncomeType::latest()->get();

        return Inertia::render('Accounting/IncomeTypes/Index', [
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:income_types,name',
        ]);

        IncomeType::create($request->only('name'));

        return redirect()->back()->with('success', 'Income type created successfully.');
    }

    public function update(Request $request, IncomeType $incomeType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:income_types,name,' . $incomeType->id,
        ]);

        $incomeType->update($request->only('name'));

        return redirect()->back()->with('success', 'Income type updated successfully.');
    }

    public function destroy(IncomeType $incomeType)
    {
        $incomeType->delete();

        return redirect()->back()->with('success', 'Income type deleted successfully.');
    }
}
