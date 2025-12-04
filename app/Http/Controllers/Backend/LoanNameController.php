<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanName;
use App\Models\LoanType;

class LoanNameController extends Controller
{
    public function index(Request $request)
    {
        $loanTypes = LoanType::where('status','active')->get();
        $query = LoanName::query();

        if($request->loan_type_id){
            $query->where('loan_type_id',$request->loan_type_id);
        }
        if($request->status){
            $query->where('status',$request->status);
        }
        if($request->from_date){
            $query->whereDate('created_at','>=',$request->from_date);
        }
        if($request->to_date){
            $query->whereDate('created_at','<=',$request->to_date);
        }

        $loanNames = $query->orderBy('created_at','desc')->get();
        return view('backend.pages.loan-name.index', compact('loanNames','loanTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'loan_name' => 'required|string|max:255',
            'interest' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        LoanName::create($request->all());
        return redirect()->back()->with('success','Loan Name created successfully');
    }

    public function edit($id)
    {
        $loanName = LoanName::findOrFail($id);
        $loanTypes = LoanType::where('status','active')->get();
        return view('backend.pages.loan-name.edit', compact('loanName','loanTypes'));
    }

    public function update(Request $request, $id)
    {
        $loanName = LoanName::findOrFail($id);
        $request->validate([
            'loan_type_id' => 'required|exists:loan_types,id',
            'loan_name' => 'required|string|max:255',
            'interest' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $loanName->update($request->all());
        return redirect()->route('admin.loan.name.index')->with('success','Loan Name updated successfully');
    }

    public function destroy($id)
    {
        LoanName::findOrFail($id)->delete();
        return redirect()->back()->with('success','Loan Name deleted successfully');
    }
}
