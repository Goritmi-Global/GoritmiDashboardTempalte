<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Account extends Model
{
    protected $fillable = [
    'type',
    'amount',
    'date',
    'reference',
    'description',
    'user_id',
    'account_country',
    'bank_id',
    'cashbook_id',
];


    public function sourceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
}
