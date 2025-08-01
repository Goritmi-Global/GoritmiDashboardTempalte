<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];
}

