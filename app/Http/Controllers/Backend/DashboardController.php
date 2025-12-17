<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apply;
use App\Models\LoanType;
use App\Models\LoanName;
use App\Models\User;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== APPLICATION COUNTS =====
        $totalApplications = Apply::count();
        $totalPending = Apply::where('status', 'pending')->count();
        $totalRejected = Apply::where('status', 'rejected')->count();

        $totalApproved = Apply::whereIn('status', ['approved', 'loan_given'])->count();
        $totalLoanGiven = Apply::where('status', 'loan_given')->count();
        $totalClosed = Apply::where('status', 'closed')->count();

        $totalOngoing = Apply::where('status', 'loan_given')
            ->whereColumn('paid_installments', '<', 'loan_duration')
            ->count();

        // ===== FINANCIAL COUNTS =====
        $totalGivenAmount = Apply::where('status', 'loan_given')->sum('loan_amount');
        $totalCollection = Apply::sum('paid_amount');

        // ===== LOAN META =====
        $totalLoanTypes = LoanType::count();
        $totalLoanNames = LoanName::count();

        // ===== USER / STAFF =====
        $totalUsers = User::count();          // Admins (Seeder 6)
        $totalEmployees = Employee::count();  // Employees table
        $totalStaff = $totalUsers + $totalEmployees;

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
            'totalUsers',
            'totalEmployees',
            'totalStaff'
        ));
    }
}
