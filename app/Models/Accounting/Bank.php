<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = ['name', 'account_number', 'branch_name'];

    public function accounts()
    {
        return $this->morphMany(Account::class, 'sourceable');
    }
}
