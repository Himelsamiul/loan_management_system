<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanGivenMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;

    public function __construct($loan)
    {
        $this->loan = $loan; // this will contain loan + user info
    }

    public function build()
    {
        return $this->subject('Your Loan Has Been Approved & Given')
                    ->view('emails.loan_given'); // blade for email
    }
}
