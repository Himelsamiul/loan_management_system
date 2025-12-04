<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanType;
use App\Models\LoanName;
use App\Models\Apply;

class ApplyController extends Controller
{
    public function show()
    {
        // Get Active Loan Types
        $loanTypes = LoanType::where('status', 'active')->get();

        // Get Active Loan Names with Type Relation
        $loanNames = LoanName::with('loanType')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.pages.apply.show', compact('loanTypes', 'loanNames'));

    }

    public function applyForm($id)
    {
        $loan = LoanName::findOrFail($id);
        return view('frontend.pages.apply.form', compact('loan'));
    }

    // ============================
    // Store loan application
    // ============================
    public function store(Request $request)
    {
        $request->validate([
                'loan_type_id' => 'required|exists:loan_types,id',
            'loan_name_id'      => 'required|exists:loan_names,id',
            'name'         => 'required|string|max:255',
            'father_name'       => 'required|string|max:255',
            'mother_name'       => 'required|string|max:255',
            'nid_number'        => 'required|string|max:50',
            'date_of_birth'               => 'required|date',
            'gender'            => 'required|in:male,female',
            'marital_status'    => 'required|in:single,married,divorced,widowed',
            'loan_amount'       => 'required|numeric|min:1',
            'present_address'   => 'required|string',
            'permanent_address' => 'required|string',

            // documents NOT required
            'document1' => 'nullable|mimes:pdf',
            'document2' => 'nullable|mimes:pdf',
            'document3' => 'nullable|mimes:pdf',
            'document4' => 'nullable|mimes:pdf',
            'document5' => 'nullable|mimes:pdf',
        ]);

        // ============================
        // FILE UPLOAD HANDLING
        // ============================
        $data = $request->all();

        for ($i = 1; $i <= 5; $i++) {
            $fileKey = "document_$i";

            if ($request->hasFile($fileKey)) {
                $fileName = time() . "_doc{$i}." . $request->$fileKey->extension();
                $request->$fileKey->move(public_path('uploads/loan-documents'), $fileName);
                $data[$fileKey] = $fileName;
            }
        }

        // Save into database
        Apply::create($data);

        return redirect()->back()->with('success', 'Loan application submitted successfully.');

    }

public function index()
{
    $applications = Apply::with(['loan_type', 'loan_name'])
                         ->orderBy('created_at', 'desc')
                         ->get();

    return view('backend.pages.applylist.index', compact('applications'));
}

}   