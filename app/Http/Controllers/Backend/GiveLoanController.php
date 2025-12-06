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

    // Loan details with installments & fine
    public function loanDetails($id)
    {
        $loan = Apply::with(['loan_type', 'loan_name'])->findOrFail($id);

        $interestRate = $loan->loan_name->interest ?? 0;
        $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
        $monthlyInstallment = $loan->loan_duration ? $totalAmount / $loan->loan_duration : 0;

        $loanStart = $loan->updated_at; // Loan given date
        $graceDays = 5;                  // Grace period in days
        $finePercentPerDay = 2;          // 2% per day
        $fineMaxDays = 10;               // Maximum 10 days fine

        $installments = [];

        for ($i = 0; $i < $loan->loan_duration; $i++) {
            $dueDate = $loanStart->copy()->addMonths($i + 1);
            $dueDateGrace = $dueDate->copy()->addDays($graceDays);

            $status = '';
            $fine = 0;

            if (now()->lessThan($dueDate)) {
                $status = 'Upcoming';
            } elseif (now()->between($dueDate, $dueDateGrace)) {
                $status = 'Grace Period';
            } else {
                $status = 'Late';
                // Calculate days late after grace
                $lateDays = now()->diffInDays($dueDateGrace);
                if ($lateDays > $fineMaxDays) {
                    $lateDays = $fineMaxDays;
                }
                $fine = ($monthlyInstallment * $finePercentPerDay / 100) * $lateDays;
            }

            $installments[] = [
                'month'          => $i + 1,
                'due_date'       => $dueDate->format('d M Y'),
                'due_date_grace' => $dueDateGrace->format('d M Y'),
                'amount'         => $monthlyInstallment,
                'status'         => $status,
                'fine'           => $fine,
            ];
        }

        return view('backend.pages.loan.loan-details', compact('loan', 'installments', 'totalAmount'));
    }
}
