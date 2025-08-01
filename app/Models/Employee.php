<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nic',
        'email',
        'designation',
        'contact',
        'nationality',
        'dob',
        'address',
        'photo',
        'gender',
        'marital_status',
        'status',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function joinings()
    {
        return $this->hasMany(EmployeeJoining::class);
    }

    public function departmentHistory()
    {
        return $this->hasMany(EmployeeDepartment::class);
    }

    public function currentDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
