<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{
    // Show all roles and create form
    public function index()
    {
        $roles = Role::with('employee')->latest()->get();
        $employees = Employee::where('status', 'active')->get();
        return view('backend.pages.role.index', compact('roles', 'employees'));
    }

    // Store new role
    // Store new role
public function store(Request $request)
{
    // Check if employee already has a role
    $exists = Role::where('employee_id', $request->employee_id)->first();
    if ($exists) {
        return redirect()->back()->with('error', 'This employee already has access!');
    }

    // Validation
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'gmail' => 'required|email|unique:roles,gmail',
        'password' => 'required|min:6|confirmed',
    ]);

    // Create role
    Role::create([
        'employee_id' => $request->employee_id,
        'gmail' => $request->gmail,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->back()->with('success', 'Role created successfully!');
}

    // Edit form
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $employees = Employee::where('status', 'active')->get();
        return view('backend.pages.role.edit', compact('role', 'employees'));
    }

    // Update role
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'gmail' => 'required|email|unique:roles,gmail,'.$role->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $role->employee_id = $request->employee_id;
        $role->gmail = $request->gmail;
        if($request->password){
            $role->password = Hash::make($request->password);
        }
        $role->save();

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    // Delete role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully!');
    }

    // RoleController.php
public function toggleStatus($id)
{
    $role = Role::findOrFail($id);
    $role->status = $role->status === 'active' ? 'inactive' : 'active';
    $role->save();

    return redirect()->back()->with('success', 'Employee status updated successfully.');
}


}
