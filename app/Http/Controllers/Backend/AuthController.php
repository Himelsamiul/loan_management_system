<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('backend.auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required']
        ]);

        $email = trim($request->email);
        $pass  = (string)$request->password;

        // ===== 1️⃣ Employee login via Role table =====
        $role = Role::with('employee')->where('gmail', $email)->first();

        if ($role) {
            if ($role->status === 'inactive') {
                return back()->withErrors(['email' => 'Your account is deactivated. Please contact the super admin.']);
            }



            if (Hash::check($pass, $role->password)) {
                session([
                    'user' => [
                        'role_id'       => $role->id,
                        'email'         => $role->gmail,
                        'employee_id'   => $role->employee->id,
                        'employee_name' => $role->employee->name,
                        'designation'   => $role->employee->designation,
                        'role_name'     => $role->employee->role
                    ]
                ]);
                return redirect()->route('admin.dashboard');
            }
        }

        // ===== 2️⃣ Admin login via User table =====
        $user = User::where('email', $email)->first();
        if ($user) {
            if (Hash::check($pass, $user->password)) {
                session([
                    'user' => [
                        'id'       => $user->id,
                        'name'     => $user->name,
                        'email'    => $user->email,
                        'role'     => 'Admin'
                    ]
                ]);
                return redirect()->route('admin.dashboard');
            }
        }

        // ===== 3️⃣ Invalid login =====
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Logout
    public function logout()
    {
        session()->forget('user');
        return redirect()->route('admin.login');
    }
}
