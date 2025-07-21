<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class IncomeType extends Model
{
    protected $fillable = ['name','description'];

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function forecastings()
    {
        return $this->hasMany(Forecasting::class);
    }
}
