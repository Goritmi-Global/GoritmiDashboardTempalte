<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Cashbook extends Model
{
    protected $fillable = ['name', 'balance'];

    public function accounts()
    {
        return $this->morphMany(Account::class, 'sourceable');
    }
}
