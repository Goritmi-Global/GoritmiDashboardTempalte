<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['expense_type_id', 'account_id', 'amount', 'date', 'receipt_no', 'receipt_image', 'user_id'];

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function upload()
    {
        return $this->belongsTo(\App\Models\Upload::class, 'receipt_image');
    }

}
