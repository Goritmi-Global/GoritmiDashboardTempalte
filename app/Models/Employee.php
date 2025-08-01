<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
   public function department() {
    return $this->belongsTo(Department::class);
}

public function joinings() {
    return $this->hasMany(EmployeeJoining::class);
}

public function departmentHistory() {
    return $this->hasMany(EmployeeDepartment::class);
}

}
