<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    // Show all employees + create form
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('backend.pages.employee.index', compact('employees'));
    }

    // Store new employee
    public function store(Request $request)
    {
        $request->validate([
            'id_card_number' => 'required|string|unique:employees,id_card_number',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:employees,phone',
            'address' => 'required|string',
        ]);

        Employee::create($request->all());

        return redirect()->back()->with('success','Employee created successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('backend.pages.employee.edit', compact('employee'));
    }

    // Update employee
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'id_card_number' => 'required|string|unique:employees,id_card_number,'.$employee->id,
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:employees,phone,'.$employee->id,
            'address' => 'required|string',
        ]);

        $employee->update($request->all());

        return redirect()->route('admin.employees.index')->with('success','Employee updated successfully!');
    }

    // Delete employee
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->back()->with('success','Employee deleted successfully!');
    }

    // Toggle active/deactive
    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = $employee->status === 'active' ? 'deactive' : 'active';
        $employee->save();
        return redirect()->back()->with('success','Employee status updated!');
    }
}
