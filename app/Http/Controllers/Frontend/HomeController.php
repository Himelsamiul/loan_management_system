<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
        public function home()
    {
         $loanTypes = LoanType::where('status', 'active')->get();
        return view('frontend.pages.home', compact('loanTypes'));
    }
}
