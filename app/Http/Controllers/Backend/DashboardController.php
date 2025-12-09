<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apply;
use App\Models\LoanType;
use App\Models\LoanName;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== BASIC COUNTS =====
        $totalApplications = Apply::count();
        $totalPending = Apply::where('status', 'pending')->count();
        $totalRejected = Apply::where('status', 'rejected')->count();

        // Approved means approved + loan given
        $totalApproved = Apply::whereIn('status', ['approved', 'loan_given'])->count();

        $totalLoanGiven = Apply::where('status', 'loan_given')->count();
        $totalClosed = Apply::where('status', 'closed')->count();

        // Ongoing loans (loan_given but installments not completed)
        $totalOngoing = Apply::where('status', 'loan_given')
                             ->whereColumn('paid_installments', '<', 'loan_duration')
                             ->count();

        // ===== MONEY COUNTS =====
        // Total loan disbursed
        $totalGivenAmount = Apply::where('status', 'loan_given')->sum('loan_amount');

        // Total collection (installments paid)
        $totalCollection = Apply::sum('paid_amount');


        // ===== LOAN TYPE & NAME =====
        $totalLoanTypes = LoanType::count();
        $totalLoanNames = LoanName::count();

        // ===== USER REGISTRATION =====
        $totalUsers = User::count();

        return view('backend.pages.dashboard', compact(
            'totalApplications',
            'totalPending',
            'totalRejected',
            'totalApproved',
            'totalLoanGiven',
            'totalClosed',
            'totalOngoing',
            'totalGivenAmount',
            'totalCollection',
            'totalLoanTypes',
            'totalLoanNames',
            'totalUsers'
        ));
    }
}
