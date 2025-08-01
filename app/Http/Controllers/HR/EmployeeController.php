<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Employee;
use App\Models\EmployeeJoining;
use App\Models\EmployeeSalary;
use App\Models\EmployeeDepartment;
use App\Models\Department;



class EmployeeController extends Controller
{
    public function index()
{
    $employees = \App\Models\Employee::with('department')->latest()->get();
    $departments = \App\Models\Department::all();

    return Inertia::render('HR/Employees/Index', [
        'employees' => $employees,
        'departments' => $departments,
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'nic' => 'required|string|unique:employees,nic',
        'email' => 'nullable|email|unique:employees,email',
        'designation' => 'nullable|string|max:255',
        'contact' => 'nullable|string|max:50',
        'nationality' => 'nullable|string|max:100',
        'dob' => 'nullable|date',
        'address' => 'nullable|string',
        'gender' => 'nullable|in:male,female,other',
        'marital_status' => 'nullable|in:single,married,divorced,widowed',
        'status' => 'required|in:active,on_leave,resigned,terminated',
        'department_id' => 'nullable|exists:departments,id',
    ]);

    $employee = Employee::create($validated);

    // If department_id is selected, create an employee_department record too
    if ($validated['department_id']) {
        \App\Models\EmployeeDepartment::create([
            'employee_id' => $employee->id,
            'department_id' => $validated['department_id'],
            'remarks' => 'Initial assignment',
            'transferred_by' => auth()->id(),
        ]);
    }

    return redirect()->route('hr.employees.index')->with('success', 'Employee created successfully.');
}



   public function joining($id) {
    // dd($id);
    $joining = EmployeeJoining::where('employee_id', $id)->latest()->get();
    // dd($joining);
    return response()->json($joining);
}

public function storeJoining(Request $request, $id)
{
    $validated = $request->validate([
        'joining_date' => 'required|date',
        'starting_salary' => 'required|numeric',
        'probation_from' => 'nullable|date',
        'probation_to' => 'nullable|date',
        'contract_type' => 'required|string',
        'contract_document' => 'nullable|file',
        'notes' => 'nullable',
    ]);

    if ($request->hasFile('contract_document')) {
        $validated['contract_document'] = $request->file('contract_document')->store('contracts', 'public');
    }

    $validated['employee_id'] = $id;
    $validated['created_by'] = auth()->id();

    // Check if joining already exists for the employee
    $joining = EmployeeJoining::where('employee_id', $id)->first();

    if ($joining) {
        $joining->update($validated);
    } else {
        EmployeeJoining::create($validated);
    }

    return back()->with('success', 'Joining record saved.');
}


public function salaryHistory($id) {
    // dd($id);
    $salaries = EmployeeSalary::where('employee_id', $id)->latest()->get();
    return response()->json($salaries);
}

public function storeSalary(Request $request, $id) {
    $validated = $request->validate([
        'amount' => 'required|numeric',
        'effective_from' => 'required|date',
        'reason' => 'nullable|string',
    ]);

    $validated['employee_id'] = $id;
    $validated['created_by'] = auth()->id();

    // Close previous salary if any
    EmployeeSalary::where('employee_id', $id)
        ->whereNull('effective_to')
        ->update(['effective_to' => $validated['effective_from']]);

    EmployeeSalary::create($validated);
    return back()->with('success', 'Salary updated.');
}

public function departmentHistory($id) {
    $history = EmployeeDepartment::with('department')->where('employee_id', $id)->latest()->get();
    // dd($history);
    return response()->json($history);
}

public function storeDepartmentAssignment(Request $request, $id) {
    $validated = $request->validate([
        'department_id' => 'required|exists:departments,id',
        'remarks' => 'nullable|string',
    ]);

    EmployeeDepartment::create([
        'employee_id' => $id,
        'department_id' => $validated['department_id'],
        'remarks' => $validated['remarks'],
        'transferred_by' => auth()->id(),
    ]);

    Employee::where('id', $id)->update(['department_id' => $validated['department_id']]);

    return back()->with('success', 'Department assigned.');
}

public function show($id)
{
    $employee = Employee::with('department')->findOrFail($id);
// dd($employee);
    return Inertia::render('HR/Employees/Show', [
        'employee' => $employee,
    ]);
}


}
