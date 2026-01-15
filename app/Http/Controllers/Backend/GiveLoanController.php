<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apply;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanPaidMail;
use App\Mail\LoanGivenMail;

class GiveLoanController extends Controller
{
    // List approved loans
public function index(Request $request)
{
    $query = Apply::with(['loan_type', 'loan_name', 'user'])
                  ->where('status', 'approved'); // only approved loans

    // Filter by Loan Type
    if ($request->loan_type_id) {
        $query->where('loan_type_id', $request->loan_type_id);
    }

    // Filter by Loan Name
    if ($request->loan_name_id) {
        $query->where('loan_name_id', $request->loan_name_id);
    }

    // Filter by User (Registration)
    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }

  $approvedLoans = $query->orderBy('created_at', 'desc')->paginate(35);

    // For dropdowns
    $loanTypes = \App\Models\LoanType::all();
    $loanNames = \App\Models\LoanName::all();
    $users = \App\Models\Registration::all();

    return view('backend.pages.loan.give-loan', compact('approvedLoans', 'loanTypes', 'loanNames', 'users'));
}


    // Give loan
   public function giveLoan($id)
{
    // Use leftJoin to get user info
    $loan = Apply::leftJoin('registrations', 'registrations.id', '=', 'applies.user_id')
                 ->leftJoin('loan_names', 'loan_names.id', '=', 'applies.loan_name_id')
                 ->select(
                     'applies.*',
                     'registrations.name as user_name',
                     'registrations.sure_name as user_sure_name',
                     'registrations.email as user_email',
                     'loan_names.loan_name as loan_name_name',
                     'loan_names.interest as loan_interest'
                 )
                 ->findOrFail($id);

    $loan->status = 'loan_given';
    $loan->start_date_loan = now();
    $loan->save();

    // ✅ Send email
    if($loan->user_email){
        Mail::to($loan->user_email)->send(new LoanGivenMail($loan));
    }

    return back()->with('success','Loan successfully given! Email sent.');
}



    // Loan details with installments
public function loanDetails($id)
{
    // Get loan with registration info
    $loan = Apply::with(['loan_type','loan_name'])
                ->leftJoin('registrations', 'registrations.id', 'applies.user_id')
                ->select('applies.*', 'registrations.name', 'registrations.sure_name', 'registrations.email')
                ->findOrFail($id);

    $interestRate = $loan->loan_name->interest ?? 0;
    $totalAmount = $loan->loan_amount + ($loan->loan_amount*$interestRate/100);
    $monthlyInstallment = $loan->loan_duration ? $totalAmount/$loan->loan_duration : 0;

    $loanStart = $loan->start_date_loan ? Carbon::parse($loan->start_date_loan) : now();
    $graceDays = 5;
    $finePercentPerDay = 0.2;
    $fineMaxDays = 10;

    $installments = [];
    $loanDuration = $loan->loan_duration;
    $paidInstallments = $loan->paid_installments ?? 0;

    for($i=0; $i<$loanDuration; $i++){
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
            $lateDays = min($dueDateGrace->diffInDays($today), $fineMaxDays);
            $fine = ($monthlyInstallment*$finePercentPerDay/100)*$lateDays;
        }

        $isPaid = ($i+1) <= $paidInstallments;

        $installments[] = [
            'month' => $i+1,
            'due_date' => $dueDate->format('d M Y'),
            'due_date_grace' => $dueDateGrace->format('d M Y'),
            'amount' => round($monthlyInstallment,2),
            'status' => $status,
            'fine' => round($fine,2),
            'is_paid' => $isPaid,
            'can_pay' => ($i+1) == ($paidInstallments+1),
            'paid_date' => $isPaid ? $loan->updated_at->format('d M Y') : null // store real payment date
        ];
    }

    return view('backend.pages.loan.loan-details', compact('loan','installments','totalAmount'));
}
public function givenLoans(Request $request)
{
$query = Apply::with(['loan_type','loan_name','user'])
              ->whereIn('status', ['loan_given', 'closed']);


    // Filter by Loan Type
    if ($request->loan_type_id) {
        $query->where('loan_type_id', $request->loan_type_id);
    }

    // Filter by Loan Name
    if ($request->loan_name_id) {
        $query->where('loan_name_id', $request->loan_name_id);
    }

    // Filter by User
    if ($request->user_id) {
        $query->where('user_id', $request->user_id);
    }

    $givenLoans = $query->paginate(30);

    $loanTypes = \App\Models\LoanType::all();
    $loanNames = \App\Models\LoanName::all();
    $users = \App\Models\Registration::all();

    return view('backend.pages.loan.give-loans', compact('givenLoans','loanTypes','loanNames','users'));
}

    // Pay installment
    public function payInstallment(Request $request, $id)
{
    // return $request->input();
$loan = Apply::with('loan_name')
    ->leftJoin('registrations', 'registrations.id', 'applies.user_id')
    ->select('applies.*', 'registrations.name', 'registrations.sure_name', 'registrations.email')
    ->findOrFail($id);


    $loan->paid_installments += 1;
    $loan->paid_amount += ($request->amount + $request->fine);

    $interestRate = $loan->loan_name->interest ?? 0;
    $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);

    if ($loan->paid_amount >= $totalAmount) {
        $loan->status = 'closed';
    }

    $loan->save();

    // ✅ Send email after payment
    $installment = [
        'month' => $request->month,
        'amount' => $request->amount,
        'fine' => $request->fine
    ];

    if($loan->email){ // make sure email exists
        Mail::to($loan->email)->send(new LoanPaidMail($loan, $installment));
    }

    return back()->with('success','Installment Paid Successfully! Email sent.');
}
}
