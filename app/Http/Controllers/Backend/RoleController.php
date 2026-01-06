<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{
    /**
     * Ensure only Super Admin can do write operations
     */
    private function ensureSuperAdmin()
    {
        if (!session()->has('user.role') || session('user.role') !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    // =========================
    // Show all roles
    // =========================
    public function index()
    {
        $roles = Role::with('employee')->latest()->get();
        $employees = Employee::where('status', 'active')->get();

        return view('backend.pages.role.index', compact('roles', 'employees'));
    }

    // =========================
    // Store new role
    // =========================
    public function store(Request $request)
    {
        $this->ensureSuperAdmin();

        // Check if employee already has role
        $exists = Role::where('employee_id', $request->employee_id)->first();
        if ($exists) {
            return redirect()->back()->with('error', 'This employee already has access!');
        }

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'gmail'       => 'required|email|unique:roles,gmail',
            'password'    => 'required|min:6|confirmed',
        ]);

        Role::create([
            'employee_id' => $request->employee_id,
            'gmail'       => $request->gmail,
            'password'    => Hash::make($request->password),
            'status'      => 'active',
        ]);

        return redirect()->back()->with('success', 'Role created successfully!');
    }

    // =========================
    // Edit role
    // =========================
    public function edit($id)
    {
        $this->ensureSuperAdmin();

        $role = Role::findOrFail($id);
        $employees = Employee::where('status', 'active')->get();

        return view('backend.pages.role.edit', compact('role', 'employees'));
    }

    // =========================
    // Update role
    // =========================
    public function update(Request $request, $id)
    {
        $this->ensureSuperAdmin();

        $role = Role::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'gmail'       => 'required|email|unique:roles,gmail,' . $role->id,
            'password'    => 'nullable|min:6|confirmed',
        ]);

        $role->employee_id = $request->employee_id;
        $role->gmail = $request->gmail;

        if ($request->password) {
            $role->password = Hash::make($request->password);
        }

        $role->save();

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully!');
    }

    // =========================
    // Delete role
    // =========================
    public function destroy($id)
    {
        $this->ensureSuperAdmin();

        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->back()->with('success', 'Role deleted successfully!');
    }

    // =========================
    // Toggle status
    // =========================
    public function toggleStatus($id)
    {
        $this->ensureSuperAdmin();

        $role = Role::findOrFail($id);
        $role->status = $role->status === 'active' ? 'inactive' : 'active';
        $role->save();

        return redirect()->back()->with('success', 'Employee status updated successfully.');
    }
}
