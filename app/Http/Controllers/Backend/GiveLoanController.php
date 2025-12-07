<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apply;
use Carbon\Carbon;

class GiveLoanController extends Controller
{
    // List approved loans
    public function index()
    {
        $approvedLoans = Apply::where('status','approved')->get();
        return view('backend.pages.loan.give-loan', compact('approvedLoans'));
    }

    // Give loan
    public function giveLoan($id)
    {
        $loan = Apply::findOrFail($id);
        $loan->status = 'loan_given';
        $loan->start_date_loan = now();
        $loan->save();

        return back()->with('success','Loan successfully given!');
    }

    // List given loans
    public function givenLoans()
    {
        $givenLoans = Apply::where('status','loan_given')->get();
        return view('backend.pages.loan.give-loans', compact('givenLoans'));
    }

    // Loan details with installments
    public function loanDetails($id)
    {
        $loan = Apply::with(['loan_type','loan_name'])->findOrFail($id);

        $interestRate = $loan->loan_name->interest ?? 0;
        $totalAmount = $loan->loan_amount + ($loan->loan_amount*$interestRate/100);
        $monthlyInstallment = $loan->loan_duration ? $totalAmount/$loan->loan_duration : 0;

        $loanStart = $loan->start_date_loan ? Carbon::parse($loan->start_date_loan) : now();

        $graceDays = 5;
        $finePercentPerDay = 2;
        $fineMaxDays = 10;

        $installments = [];
        $loanDuration = $loan->loan_duration;
        $paid = $loan->paid_installments ?? 0;

        for($i=0;$i<$loanDuration;$i++){
            $dueDate = $loanStart->copy()->addMonths($i+1);
            $dueDateGrace = $dueDate->copy()->addDays($graceDays);

            $today = now();
            $status = '';
            $fine = 0;
            $lateDays = 0;

            if($today->lt($dueDate)){
                $status = 'Upcoming';
            } elseif($today->between($dueDate,$dueDateGrace)){
                $status = 'Grace Period';
            } else {
                $status = 'Late';
                $lateDays = min($dueDateGrace->diffInDays($today),$fineMaxDays);
                $fine = ($monthlyInstallment*$finePercentPerDay/100)*$lateDays;
            }

            $installments[] = [
                'month'=>$i+1,
                'due_date'=>$dueDate->format('d M Y'),
                'due_date_grace'=>$dueDateGrace->format('d M Y'),
                'amount'=>round($monthlyInstallment,2),
                'status'=>$status,
                'fine'=>round($fine,2),
                'is_paid'=>($i+1)<=$paid,
                'can_pay'=>($i+1)==($paid+1)
            ];
        }

        return view('backend.pages.loan.loan-details', compact('loan','installments','totalAmount'));
    }

    // Pay installment
    public function payInstallment(Request $request, $id)
    {
        $loan = Apply::findOrFail($id);

        $loan->paid_installments +=1;
        $loan->paid_amount += ($request->amount+$request->fine);

        $interestRate = $loan->loan_name->interest ?? 0;
        $totalAmount = $loan->loan_amount + ($loan->loan_amount*$interestRate/100);

        if($loan->paid_amount >= $totalAmount){
            $loan->status = 'closed';
        }

        $loan->save();

        return back()->with('success','Installment Paid Successfully!');
    }
}
