<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanApplicationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $status;

    public function __construct($loan, $status)
    {
        $this->loan = $loan;
        $this->status = $status; // approved / rejected
    }

    public function build()
    {
        return $this->subject('Your Loan Application Status Update')
                    ->view('emails.loan_status');
    }
}
