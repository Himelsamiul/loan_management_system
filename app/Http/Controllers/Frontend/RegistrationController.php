<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Apply;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    // Show Registration Form
    public function create()
    {
        return view('frontend.pages.registration.create');
    }

    // Store Registration
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'sure_name'     => 'required|string|max:255',
            'mobile'        => 'required|numeric|digits_between:10,20|unique:registrations,mobile',
            'email'         => 'required|email|unique:registrations,email',
            'address'       => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'password'      => 'required|min:6|confirmed',
        ]);

        Registration::create([
            'name'          => $request->name,
            'sure_name'     => $request->sure_name,
            'mobile'        => $request->mobile,
            'email'         => $request->email,
            'address'       => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'password'      => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration Successful!');
    }

public function profile()
{
    $user = Auth::user();

    // Fetch only applications submitted by this user
    $applications = Apply::with(['loan_type', 'loan_name'])
                          ->where('user_id', $user->id)
                         ->orderBy('created_at', 'desc')
                         ->get();
 
    return view('frontend.pages.registration.view', compact('user', 'applications'));
}


    // Edit Profile of logged-in user
    public function edit()
    {
        $user = Auth::user(); // Get currently logged-in user
        return view('frontend.pages.registration.edit', compact('user'));
    }

    // Update Profile of logged-in user
    public function update(Request $request)
    {
        $user = Auth::user(); // Get currently logged-in user

        $request->validate([
            'name'          => 'required|string|max:255',
            'sure_name'     => 'required|string|max:255',
            'mobile'        => 'required|numeric|digits_between:10,20|unique:registrations,mobile,' . $user->id,
            'email'         => 'required|email|unique:registrations,email,' . $user->id,
            'address'       => 'required|string|max:255',
            'date_of_birth' => 'required|date',
        ]);

        $user->update($request->all());

        return redirect()->route('profile.view')->with('success', 'Profile Updated Successfully!');
    }

    // Backend: List Users
    public function index()
    {
        $users = Registration::latest()->get();
        return view('backend.user.index', compact('users'));
    }

    // Backend: Delete User
    public function destroy($id)
    {
        Registration::findOrFail($id)->delete();
        return back()->with('success', 'User deleted successfully!');
    }


    public function viewInstallments($id)
{
    $loan = Apply::with(['loan_type', 'loan_name'])
                 ->where('user_id', Auth::id())
                 ->findOrFail($id);

    $interestRate = $loan->loan_name->interest ?? 0;
    $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
    $monthlyInstallment = $loan->loan_duration ? $totalAmount / $loan->loan_duration : 0;

    $installments = [];
    $loanStart = $loan->updated_at; // When loan was given
    for ($i = 0; $i < $loan->loan_duration; $i++) {
        $dueDate = $loanStart->copy()->addMonths($i);
        $installments[] = [
            'month' => $i + 1,
            'due_date' => $dueDate->format('d M Y'),
            'amount' => number_format($monthlyInstallment, 2),
        ];
    }

    return view('frontend.pages.registration.installments', compact('loan', 'installments', 'totalAmount'));
}

}
