<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
   public function joining($id) {
    $joining = EmployeeJoining::where('employee_id', $id)->latest()->first();
    return response()->json($joining);
}

public function storeJoining(Request $request, $id) {
    $validated = $request->validate([
        'joining_date' => 'required|date',
        'starting_salary' => 'required|numeric',
        'probation_from' => 'nullable|date',
        'probation_to' => 'nullable|date',
        'contract_type' => 'required|string',
        'contract_document' => 'nullable|file',
    ]);

    if ($request->hasFile('contract_document')) {
        $validated['contract_document'] = $request->file('contract_document')->store('contracts', 'public');
    }

    $validated['employee_id'] = $id;
    $validated['created_by'] = auth()->id();

    EmployeeJoining::create($validated);
    return back()->with('success', 'Joining record saved.');
}

public function salaryHistory($id) {
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

}
