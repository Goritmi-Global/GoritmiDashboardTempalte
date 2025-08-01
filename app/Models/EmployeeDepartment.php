<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDepartment extends Model
{
    protected $fillable = [
        'employee_id',
        'department_id',
        'remarks',
        'assigned_at',
        'transferred_by',
    ];

    public $timestamps = false;

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the employee associated with the department history.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the department that was assigned.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who transferred the employee.
     */
    public function transferredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}
