<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanTypeController extends Controller
{
    // Show create page + list
    public function index(Request $request)
{
    $query = LoanType::query();

    if ($request->from_date) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    $loanTypes = $query->orderBy('created_at', 'desc')->get();

    return view('backend.pages.loan-types.index', compact('loanTypes'));
}


    // Store loan type (default active)
    public function store(Request $request)
    {
        $request->validate([
            'loan_name' => 'required|unique:loan_types,loan_name',
        ]);

        LoanType::create([
            'loan_name' => $request->loan_name,
            'status' => 'active', // always active by default
        ]);

        return back()->with('success', 'Loan type created successfully!');
    }

    // Edit page
    public function edit($id)
    {
        $loanType = LoanType::findOrFail($id);
        return view('backend.pages.loan-types.edit', compact('loanType'));
    }

    // Update loan type (can change status here)
   public function update(Request $request, $id)
{
    $loanType = LoanType::findOrFail($id);

    // Validation
    if ($loanType->is_used) {
        // If used → only status can be changed
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $loanType->update([
            'status' => $request->status,
        ]);
    } else {
        // If not used → full edit allowed
        $request->validate([
            'loan_name' => 'required|unique:loan_types,loan_name,' . $loanType->id,
            'status'    => 'required|in:active,inactive',
        ]);

        $loanType->update([
            'loan_name' => $request->loan_name,
            'status'    => $request->status,
        ]);
    }

    return redirect()
        ->route('admin.loan.type.index')
        ->with('success', 'Loan type updated successfully!');
}

}
