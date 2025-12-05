<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanType;
use App\Models\LoanName;
use App\Models\Apply;
use Illuminate\Support\Facades\Auth;

class ApplyController extends Controller
{
    // Show all active loan types and names (frontend)
    public function show()
    {
        $loanTypes = LoanType::where('status', 'active')->get();
        $loanNames = LoanName::with('loanType')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.pages.apply.show', compact('loanTypes', 'loanNames'));
    }

    // Show loan application form
    public function applyForm($id)
    {
        $loan = LoanName::findOrFail($id);
        return view('frontend.pages.apply.form', compact('loan'));
    }

    // Show review page before final submission
    public function review(Request $request)
    {
        $request->validate([
            'loan_type_id'      => 'required|exists:loan_types,id',
            'loan_name_id'      => 'required|exists:loan_names,id',
            'name'              => 'required|string|max:255',
            'father_name'       => 'required|string|max:255',
            'mother_name'       => 'required|string|max:255',
            'nid_number'        => 'required|string|max:50',
            'date_of_birth'     => 'required|date',
            'gender'            => 'required|in:male,female',
            'marital_status'    => 'required|in:single,married,divorced,widowed',
            'loan_amount'       => 'required|numeric|min:1',
            'loan_duration'     => 'required|numeric|min:1',
            'present_address'   => 'required|string',
            'permanent_address' => 'required|string',
        ]);

        // Fetch loan info for interest calculation
        $loan = LoanName::findOrFail($request->loan_name_id);

        $loan_amount = $request->loan_amount;
        $interest_rate = $loan->interest; // Assuming interest is in percentage
        $interest_amount = ($loan_amount * $interest_rate) / 100;
        $total_amount = $loan_amount + $interest_amount;
        $monthly_installment = $total_amount / $request->loan_duration;

        // Pass all data to review view
        return view('frontend.pages.apply.review', [
            'data' => $request->all(),
            'loan' => $loan,
            'interest_amount' => $interest_amount,
            'total_amount' => $total_amount,
            'monthly_installment' => $monthly_installment,
        ]);
    }

    // Final submission after review
    public function store(Request $request)
    {
        $request->validate([
            'loan_type_id'      => 'required|exists:loan_types,id',
            'loan_name_id'      => 'required|exists:loan_names,id',
            'name'              => 'required|string|max:255',
            'father_name'       => 'required|string|max:255',
            'mother_name'       => 'required|string|max:255',
            'nid_number'        => 'required|string|max:50',
            'date_of_birth'     => 'required|date',
            'gender'            => 'required|in:male,female',
            'marital_status'    => 'required|in:single,married,divorced,widowed',
            'loan_amount'       => 'required|numeric|min:1',
            'loan_duration'     => 'required|numeric|min:1',
            'present_address'   => 'required|string',
            'permanent_address' => 'required|string',
        ]);

        $data = $request->all();
        $data['status'] = 'pending';
        $data['user_id'] = Auth::id();
        // Handle file uploads
        for ($i = 1; $i <= 5; $i++) {
            $fileKey = "document$i";
            if ($request->hasFile($fileKey)) {
                $fileName = time() . "_doc{$i}." . $request->$fileKey->extension();
                $request->$fileKey->move(public_path('uploads/loan-documents'), $fileName);
                $data[$fileKey] = $fileName;
            }
        }

        Apply::create($data);

        return redirect()->route('frontend.apply.show')->with('success', 'Loan application submitted successfully.');
    }

    // Backend: List all applications
    public function index()
    {
        $applications = Apply::with(['loan_type', 'loan_name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.pages.applylist.index', compact('applications'));
    }

    // Backend: Approve/Reject application
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $application = Apply::findOrFail($id);
        $application->status = $request->status;
        $application->save();

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }
}
