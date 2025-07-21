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
}
