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

    // User Profile
    public function profile()
    {
        $user = Auth::user();

        $applications = Apply::with(['loan_type', 'loan_name'])
                              ->where('user_id', $user->id)
                              ->orderBy('created_at', 'desc')
                              ->get();

        // Attach a computed field 'status_label' for easier Blade handling
        foreach($applications as $app){
            if($app->status == 'loan_given' && $app->paid_installments >= $app->loan_duration){
                $app->status_label = 'Closed';
            } elseif($app->status == 'closed') {
                $app->status_label = 'Closed';
            } elseif($app->status == 'pending') {
                $app->status_label = 'Pending';
            } elseif($app->status == 'approved') {
                $app->status_label = 'Approved';
            } elseif($app->status == 'loan_given') {
                $app->status_label = 'Given';
            } else {
                $app->status_label = 'Rejected';
            }
        }

        return view('frontend.pages.registration.view', compact('user', 'applications'));
    }

    // Edit Profile
    public function edit()
    {
        $user = Auth::user();
        return view('frontend.pages.registration.edit', compact('user'));
    }

    // Update Profile
    public function update(Request $request)
    {
        $user = Auth::user();

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

    // View Installments
    public function viewInstallments($id)
    {
        $loan = Apply::with(['loan_type', 'loan_name'])
                     ->where('user_id', Auth::id())
                     ->findOrFail($id);

        $interestRate = $loan->loan_name->interest ?? 0;
        $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
        $monthlyInstallment = $loan->loan_duration ? $totalAmount / $loan->loan_duration : 0;

        $loanStart = $loan->start_date_loan
                        ? \Carbon\Carbon::parse($loan->start_date_loan)
                        : $loan->updated_at;

        $graceDays = 5;
        $finePercentPerDay = 2;
        $fineMaxDays = 10;

        $installments = [];

        for ($i = 0; $i < $loan->loan_duration; $i++) {

            $dueDate = $loanStart->copy()->addMonths($i + 1);
            $dueDateGrace = $dueDate->copy()->addDays($graceDays);

            $today = now();
            $status = '';
            $fine = 0;

            if ($today->lt($dueDate)) {
                $status = 'Upcoming';
            } elseif ($today->between($dueDate, $dueDateGrace)) {
                $status = 'Grace Period';
            } else {
                $status = 'Late';
                $lateDays = min($dueDateGrace->diffInDays($today), $fineMaxDays);
                $fine = ($monthlyInstallment * $finePercentPerDay / 100) * $lateDays;
            }

            $isPaid = ($i + 1) <= $loan->paid_installments;
            $paidAmount = $isPaid ? $monthlyInstallment + $fine : 0;
            $paidDate = $isPaid ? $loan->updated_at->format('d M Y') : null;

            $installments[] = [
                'month'           => $i + 1,
                'due_date'        => $dueDate->format('d M Y'),
                'due_date_grace'  => $dueDateGrace->format('d M Y'),
                'amount'          => round($monthlyInstallment, 2),
                'status'          => $status,
                'fine'            => round($fine, 2),
                'paid_amount'     => round($paidAmount, 2),
                'paid_date'       => $paidDate,
            ];
        }

        return view('frontend.pages.registration.installments', compact('loan', 'installments', 'totalAmount'));
    }
public function destroy($id)
{
    $user = Registration::findOrFail($id);
    $user->delete();

    return redirect()->back()->with('success', 'User deleted successfully!');
}
public function index(Request $request)
{
    $query = Registration::query();

    if ($request->name) {
        $query->where('name', 'LIKE', '%' . $request->name . '%');
    }

    if ($request->mobile) {
        $query->where('mobile', 'LIKE', '%' . $request->mobile . '%');
    }

    if ($request->email) {
        $query->where('email', 'LIKE', '%' . $request->email . '%');
    }

    $users = $query->orderBy('created_at', 'desc')->paginate(15);

    return view('backend.user.index', compact('users'));
}

}
