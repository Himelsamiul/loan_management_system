<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanAppliedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loanData;

    /**
     * Create a new message instance.
     */
    public function __construct($loanData)
    {
        $this->loanData = $loanData;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Loan Application Submitted Successfully')
                    ->view('emails.loan_applied');
    }
}
