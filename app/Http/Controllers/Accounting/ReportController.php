<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\Account;
 use Inertia\Inertia;
 

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
}
