<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apply;
use Carbon\Carbon;

class GiveLoanController extends Controller
{
    // Show approved loans
    public function index()
    {
        $approvedLoans = Apply::where('status', 'approved')->get();
        return view('backend.pages.loan.give-loan', compact('approvedLoans'));
    }

    // Give Loan Action
    public function giveLoan($id)
    {
        $loan = Apply::findOrFail($id);
        $loan->status = 'loan_given';
        $loan->save();

        return back()->with('success', 'Loan successfully given!');
    }

    // Show given loans
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

    // Ensure start date exists
    $loanStart = $loan->start_date_loan ? \Carbon\Carbon::parse($loan->start_date_loan) : now();

    $graceDays = 5;           // Grace period in days
    $finePercentPerDay = 2;   // 2% per day
    $fineMaxDays = 10;        // Maximum 10 days fine

    $installments = [];
    $loanDuration = $loan->loan_duration;

    for ($i = 0; $i < $loanDuration; $i++) {

        $dueDate = $loanStart->copy()->addMonths($i + 1);
        $dueDateGrace = $dueDate->copy()->addDays($graceDays);

        $today = now();
        $status = '';
        $fine = 0;
        $lateDays = 0;
        $fineStartDate = null;

        if ($today->lt($dueDate)) {
            $status = 'Upcoming';
        } elseif ($today->between($dueDate, $dueDateGrace)) {
            $status = 'Grace Period';
        } else {
            $status = 'Late';

            // Only calculate late days if today is after grace period
            if ($today->gt($dueDateGrace)) {
                $lateDays = $dueDateGrace->diffInDays($today); // always positive
                $lateDays = min($lateDays, $fineMaxDays);

                $fine = ($monthlyInstallment * $finePercentPerDay / 100) * $lateDays;

                $fineStartDate = $dueDateGrace->copy()->addDay()->format('d M Y');
            }
        }

        $installments[] = [
            'month'           => $i + 1,
            'due_date'        => $dueDate->format('d M Y'),
            'due_date_grace'  => $dueDateGrace->format('d M Y'),
            'amount'          => round($monthlyInstallment, 2),
            'status'          => $status,
            'fine'            => round($fine, 2),
            'late_days'       => $lateDays,
            'fine_start_date' => $fineStartDate,
        ];
    }

    // return $installments;
    return view('backend.pages.loan.loan-details', compact('loan', 'installments', 'totalAmount'));
}


}
