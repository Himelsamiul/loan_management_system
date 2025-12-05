<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apply;

class GiveLoanController extends Controller
{
    // Approved loans দেখানোর ফাংশন
    public function index()
    {
        $approvedLoans = Apply::where('status', 'approved')->get();
        return view('backend.pages.loan.give-loan', compact('approvedLoans'));
    }

    // Give Loan Action
    public function giveLoan($id)
    {
        $loan = Apply::findOrFail($id);

        // Loan দেওয়ার কাজ এখানে
        $loan->status = 'loan_given';
        $loan->save();

        return back()->with('success', 'Loan successfully given!');


        
    }

  public function givenLoans()
    {
        $givenLoans = Apply::where('status', 'loan_given')->get();
        return view('backend.pages.loan.give-loans', compact('givenLoans'));
    }


    public function loanDetails($id)
{
    $loan = Apply::with(['loan_type', 'loan_name'])->findOrFail($id);
    
    $interestRate = $loan->loan_name->interest ?? 0;
    $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
    $monthlyInstallment = $loan->loan_duration ? $totalAmount / $loan->loan_duration : 0;

    $installments = [];
    $loanStart = $loan->updated_at; // Loan given date
    for($i = 0; $i < $loan->loan_duration; $i++) {
        $dueDate = $loanStart->copy()->addMonths($i);
        $installments[] = [
            'month' => $i + 1,
            'due_date' => $dueDate->format('d M Y'),
            'amount' => number_format($monthlyInstallment, 2),
            'status' => $dueDate->isPast() ? 'Pending' : 'Upcoming'
        ];
    }

    return view('backend.pages.loan.loan-details', compact('loan', 'installments', 'totalAmount'));
}

}
