<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['income_type_id', 'account_id', 'amount', 'date', 'receipt_no', 'receipt_image', 'user_id'];

    public function incomeType()
    {
        return $this->belongsTo(IncomeType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
