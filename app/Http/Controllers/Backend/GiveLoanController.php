<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apply;

class GiveLoanController extends Controller
{
    // Approved loans দেখানোর ফাংশন
    public function index()
    {
        $approvedLoans = Apply::where('status', 'approved')->get();
        return view('backend.pages.loan.give-loan', compact('approvedLoans'));
    }

    // Give Loan Action
    public function giveLoan($id)
    {
        $loan = Apply::findOrFail($id);

        // Loan দেওয়ার কাজ এখানে
        $loan->status = 'loan_given';
        $loan->save();

        return back()->with('success', 'Loan successfully given!');


        
    }

  public function givenLoans()
    {
        $givenLoans = Apply::where('status', 'loan_given')->get();
        return view('backend.pages.loan.give-loans', compact('givenLoans'));
    }
}
