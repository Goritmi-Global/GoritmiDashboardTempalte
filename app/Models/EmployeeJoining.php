<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeJoining extends Model
{
    protected $fillable = [
        'employee_id',
        'joining_date',
        'starting_salary',
        'probation_from',
        'probation_to',
        'contract_type',
        'contract_document',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'joining_date' => 'date:Y-m-d',
        'probation_from' => 'date:Y-m-d',
        'probation_to' => 'date:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];
}

