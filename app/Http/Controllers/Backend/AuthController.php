<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('backend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required']
        ]);

        $email = trim($request->email);
        $pass  = (string)$request->password;

        // Try to find user
        $user = User::where('email', $email)->first();

        if ($user && (
            $user->password === $pass ||                     // plain text
            Hash::check($pass, $user->password)             // hashed password
        )) {
            // Save user in session
            session(['user' => $user]);
            return redirect()->route('admin.dashboard');
        }

        // Invalid login
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        session()->forget('user');
        return redirect()->route('admin.login');
    }
}
