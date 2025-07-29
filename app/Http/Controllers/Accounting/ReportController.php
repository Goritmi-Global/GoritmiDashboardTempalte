<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\Account;
 use Inertia\Inertia;
 use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    { 
         
        $query = Account::with(['sourceable', 'incomes', 'expenses']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('date', [$request->from, $request->to]);
        } elseif ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                  ->whereYear('date', $request->year);
        } elseif ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $reports = $query->orderBy('date', 'desc')->get();

        return inertia('Accounting/Reports/Index', [
            'reports' => $reports,
        ]);
    
}
public function generateMonthlyExpenseForecast()
{
    $salary = 200000;

    $last3Months = Carbon::now()->subMonths(3);
    $expenses = Account::where('type', 'expense')
        ->whereDate('date', '>=', $last3Months)
        ->get()
        ->groupBy(function($txn) {
            return Carbon::parse($txn->date)->format('Y-m');
        });

    $totalExpense = $expenses->map(function($group) {
        return $group->sum('amount');
    })->avg();

    $forecastAmount = $totalExpense + $salary;

    // Get total income and total expenses from the start
    $totalIncome = Account::whereIn('type', ['income', 'opening_balance'])->sum('amount');
    $totalExpenses = Account::where('type', 'expense')->sum('amount');
    $netProfit = $totalIncome - $totalExpenses;

    return Inertia::render('Accounting/Reports/Forecast', [
        'forecastAmount' => $forecastAmount,
        'salary' => $salary,
        'netProfit' => $netProfit,
        'totalIncome' => $totalIncome,
        'totalExpenses' => $totalExpenses,
    ]);
}

}
