<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    protected $fillable = ['name','description'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function forecastings()
    {
        return $this->hasMany(Forecasting::class);
    }
}
