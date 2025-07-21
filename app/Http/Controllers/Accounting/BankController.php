<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Bank;
use Illuminate\Http\Request;
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
            'balance' => 'nullable|numeric',
        ]);

        Bank::create($request->only('name', 'account_number', 'branch', 'balance'));

        return redirect()->back()->with('success', 'Bank created successfully.');
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:banks,name,' . $bank->id,
            'account_number' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric',
        ]);

        $bank->update($request->only('name', 'account_number', 'branch', 'balance'));

        return redirect()->back()->with('success', 'Bank updated successfully.');
    }

    public function destroy(Bank $bank)
    {
        try {
            // Check for any dependent data if necessary
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
