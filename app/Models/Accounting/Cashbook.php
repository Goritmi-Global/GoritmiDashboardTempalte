<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Cashbook extends Model
{
    protected $fillable = ['name', 'location', 'description'];

    public function accounts()
    {
        return $this->morphMany(Account::class, 'sourceable');
    }
}
