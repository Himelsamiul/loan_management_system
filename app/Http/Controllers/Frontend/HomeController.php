<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;
use App\Models\ContactMessage;

class HomeController extends Controller
{
        public function home()
    {
         $loanTypes = LoanType::where('status', 'active')->get();
        return view('frontend.pages.home', compact('loanTypes'));
    }

    public function aboutus()
    {
        return view('frontend.pages.aboutus');
    }
    public function blogs()
    {
        return view('frontend.pages.blogs');
    }
    public function service()
    {
        return view('frontend.pages.service');
    }


    public function contactSubmit(Request $request)
{
    $request->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email',
        'phone'   => 'required|string|max:20',
        'message' => 'required|string',
    ]);

    ContactMessage::create($request->all());

    return back()->with('success', 'Your message has been sent successfully!');
}


public function contactMessages()
{
    $messages = ContactMessage::latest()->paginate(10);
    return view('backend.contact_messages.index', compact('messages'));
}
}
