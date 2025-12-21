<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanType;
use App\Models\LoanName;
use App\Models\Apply;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanApplicationSubmitted;
use App\Mail\LoanAppliedMail;
use App\Mail\LoanApplicationStatusMail;

class ApplyController extends Controller
{
    // Frontend: show all loans
     public function show()
    {
        $loanTypes = LoanType::where('status','active')->get();
        $loanNames = LoanName::with('loanType')->where('status','active')->orderBy('created_at','desc')->get();
        return view('frontend.pages.apply.show', compact('loanTypes', 'loanNames'));
    }

    // Frontend: apply form
    public function applyForm($id)
    {
        $loan = LoanName::findOrFail($id);
        return view('frontend.pages.apply.form', compact('loan'));
    }

    // Frontend: review before submission
    public function review(Request $request)
    {
        $request->validate([
            'loan_type_id'=>'required|exists:loan_types,id',
            'loan_name_id'=>'required|exists:loan_names,id',
            'name'=>'required|string|max:255',
            'father_name'=>'required|string|max:255',
            'mother_name'=>'required|string|max:255',
            'nid_number' => [
        'required',
        'numeric',
        'digits_between:9,16', // 9 to 16 digits allowed
    ],

            'date_of_birth'=>'required|date',
            'gender'=>'required|in:male,female',
            'marital_status'=>'required|in:single,married,divorced,widowed',
            'loan_amount'=>'required|numeric|min:1',
            'loan_duration'=>'required|numeric|min:1',
            'present_address'=>'required|string',
            'permanent_address'=>'required|string',
            // Mobile banking optional validation
            'mobile_provider' => 'nullable|in:bKash,Nagad,Rocket',
    'mobile_number' => [
        'nullable',
        'required_if:mobile_provider,bKash,Nagad,Rocket',
        'regex:/^\d{11}$/', // must be exactly 11 digits
    ],
                // Card mandatory validation
            'card_type' => 'required|in:Debit,Credit',
    'card_brand' => 'required|string|max:50',
    'card_number' => [
        'required',
        'numeric',
        'digits:16', // exactly 16 digits
    ],
    'card_holder' => 'required|string|max:255',
    'card_expiry' => 'required|string|max:7', // MM/YY format
    'card_cvc' => [
        'required',
        'numeric',
        'digits_between:3,4', // typically 3 or 4 digits
    ],
        ]);

        $loan = LoanName::findOrFail($request->loan_name_id);

        $loan_amount = $request->loan_amount;
        $interest_rate = $loan->interest;
        $interest_amount = ($loan_amount * $interest_rate)/100;
        $total_amount = $loan_amount + $interest_amount;
        $monthly_installment = $total_amount / $request->loan_duration;

        return view('frontend.pages.apply.review', [
            'data'=>$request->all(),
            'loan'=>$loan,
            'interest_amount'=>$interest_amount,
            'total_amount'=>$total_amount,
            'monthly_installment'=>$monthly_installment
        ]);
    }

    // Frontend: final submission
    public function store(Request $request)
    {
        $request->validate([
            'loan_type_id'=>'required|exists:loan_types,id',
            'loan_name_id'=>'required|exists:loan_names,id',
            'name'=>'required|string|max:255',
            'father_name'=>'required|string|max:255',
            'mother_name'=>'required|string|max:255',
            'nid_number' => [
        'required',
        'numeric',
        'digits_between:9,16', // 9 to 16 digits allowed
    ],
            'date_of_birth'=>'required|date',
            'gender'=>'required|in:male,female',
            'marital_status'=>'required|in:single,married,divorced,widowed',
            'loan_amount'=>'required|numeric|min:1',
            'loan_duration'=>'required|numeric|min:1',
            'present_address'=>'required|string',
            'permanent_address'=>'required|string',
            // Mobile banking optional validation
            'mobile_provider' => 'nullable|in:bKash,Nagad,Rocket',
    'mobile_number' => [
        'nullable',
        'required_if:mobile_provider,bKash,Nagad,Rocket',
        'regex:/^\d{11}$/', // must be exactly 11 digits
    ],
            // Card mandatory validation
            'card_type' => 'required|in:Debit,Credit',
    'card_brand' => 'required|string|max:50',
    'card_number' => [
        'required',
        'numeric',
        'digits:16', // exactly 16 digits
    ],
    'card_holder' => 'required|string|max:255',
    'card_expiry' => 'required|string|max:7', // MM/YY format
    'card_cvc' => [
        'required',
        'numeric',
        'digits_between:3,4', // typically 3 or 4 digits
    ],
            // Document uploads
        ]);

        $data = $request->all();
        $data['status'] = 'pending';
        $data['user_id'] = Auth::id();

        // Upload documents
        for($i=1;$i<=5;$i++){
            $fileKey = "document$i";
            if($request->hasFile($fileKey)){
                $fileName = time() . "_doc{$i}." . $request->$fileKey->extension();
                $request->$fileKey->move(public_path('uploads/loan-documents'), $fileName);
                $data[$fileKey] = $fileName;
            }
        }

        // Create loan application
        $apply = Apply::create($data);

        // Fetch user details for email
        $loan = Apply::leftJoin('registrations', 'registrations.id', 'applies.user_id')
                     ->select('applies.*', 'registrations.name', 'registrations.sure_name', 'registrations.email')
                     ->where('applies.id', $apply->id)
                     ->first();

        // Send email if email exists
        if($loan && $loan->email){
            Mail::to($loan->email)->send(new LoanAppliedMail($loan));
        }

        return redirect()->route('frontend.apply.show')
                         ->with('success','Loan application submitted successfully. An email has been sent.');
    }

    // Backend: list all applications
public function index(Request $request)
{
    $query = Apply::with(['loan_type', 'loan_name', 'user']); // use snake_case

    if ($request->loan_type_id) {
        $query->where('loan_type_id', $request->loan_type_id);
    }

    if ($request->loan_name_id) {
        $query->where('loan_name_id', $request->loan_name_id);
    }

    if ($request->customer_id) {
        $query->where('user_id', $request->customer_id); // filter by user_id
    }

    if ($request->mobile) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('mobile', 'LIKE', '%' . $request->mobile . '%');
        });
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

   $applications = $query->orderBy('created_at', 'desc')->paginate(15);

    $loanTypes = LoanType::all();
    $loanNames = LoanName::all();
    $customers = Registration::all(); // all registered users for dropdown

    return view('backend.pages.applylist.index', compact(
        'applications', 
        'loanTypes', 
        'loanNames', 
        'customers'
    ));
}


    // Backend: Approve/Reject application
   public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status'=>'required|in:approved,rejected'
    ]);

    // Fetch the application with user info using left join
    $application = Apply::leftJoin('registrations', 'registrations.id', 'applies.user_id')
                        ->select('applies.*', 'registrations.name', 'registrations.sure_name', 'registrations.email')
                        ->where('applies.id', $id)
                        ->firstOrFail();

    $application->status = $request->status;

    if($request->status == 'approved'){
        $application->start_date_loan = null;
    }

    $application->save();

    // Send email if email exists
    if($application->email){ // email comes from joined table
        Mail::to($application->email)
            ->send(new LoanApplicationStatusMail($application, $request->status));
    }

    return redirect()->back()->with('success','Application status updated successfully. Email sent.');
}
}
