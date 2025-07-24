<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Income;
use App\Models\Accounting\Account;
use Inertia\Inertia;

class IncomeController extends Controller
{
    public function index()
    { 
        $incomes = Income::with('account')->latest()->get();

        $totalIncomeAmount = Account::where('type', 'income')->sum('amount');
        $incomeCount = Account::where('type', 'income')->count();

        return Inertia::render('Accounting/Incomes/Index', [
            'incomes' => $incomes,
            'totalIncomeAmount' => $totalIncomeAmount,
            'incomeCount' => $incomeCount,
        ]);
    }
}
