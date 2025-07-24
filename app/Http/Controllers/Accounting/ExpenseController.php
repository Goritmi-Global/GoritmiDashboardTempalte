<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Expense;
use App\Models\Accounting\Account;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('account')->latest()->get();

        $totalExpenseAmount = Account::where('type', 'expense')->sum('amount');
        $expenseCount = Account::where('type', 'expense')->count();

        return Inertia::render('Accounting/Expenses/Index', [
            'expenses' => $expenses,
            'totalExpenseAmount' => $totalExpenseAmount,
            'expenseCount' => $expenseCount,
        ]);
    }
}
