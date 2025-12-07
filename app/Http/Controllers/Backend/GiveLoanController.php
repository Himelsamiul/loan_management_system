<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apply;
use Carbon\Carbon;

class GiveLoanController extends Controller
{
    /**
     * Show approved loans list
     */
    public function index()
    {
        $approvedLoans = Apply::where('status', 'approved')->get();
        return view('backend.pages.loan.give-loan', compact('approvedLoans'));
    }

    /**
     * Give loan action
     */
    public function giveLoan($id)
    {
        $loan = Apply::findOrFail($id);
        $loan->status = 'loan_given';
        $loan->save();

        return back()->with('success', 'Loan successfully given!');
    }

    /**
     * Show all given loans
     */
    public function givenLoans()
    {
        $givenLoans = Apply::where('status', 'loan_given')->get();
        return view('backend.pages.loan.give-loans', compact('givenLoans'));
    }

    /**
     * Show loan details with installment schedule
     */
    public function loanDetails($id)
    {
        $loan = Apply::with(['loan_type', 'loan_name'])->findOrFail($id);

        $interestRate = $loan->loan_name->interest ?? 0;
        $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
        $monthlyInstallment = $loan->loan_duration ? $totalAmount / $loan->loan_duration : 0;

        // Loan start date
        $loanStart = $loan->start_date_loan ? Carbon::parse($loan->start_date_loan) : now();

        $graceDays = 5;         // Grace period
        $finePercentPerDay = 2; // Fine percentage per day
        $fineMaxDays = 10;      // Maximum fine days

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
                if ($today->gt($dueDateGrace)) {
                    $lateDays = min($dueDateGrace->diffInDays($today), $fineMaxDays);
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

        return view('backend.pages.loan.loan-details', compact('loan', 'installments', 'totalAmount'));
    }
}
