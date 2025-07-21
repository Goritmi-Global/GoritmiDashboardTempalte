<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Forecasting extends Model
{
    protected $fillable = ['type', 'expected_amount', 'expected_date', 'note'];

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function incomeType()
    {
        return $this->belongsTo(IncomeType::class);
    }
}
