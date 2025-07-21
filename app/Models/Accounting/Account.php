<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'amount',
        'type', // cashbook or bank
        'bank_id',
        'cashbook_id',
        'user_id',
    ];

    // public function bank()
    // {
    //     return $this->belongsTo(Bank::class);
    // }

    // public function cashbook()
    // {
    //     return $this->belongsTo(Cashbook::class);
    // }
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
