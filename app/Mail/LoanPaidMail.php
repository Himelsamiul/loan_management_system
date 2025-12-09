<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $installment;

    public function __construct($loan, $installment)
    {
        $this->loan = $loan;
        $this->installment = $installment;
    }

    public function build()
    {
        return $this->subject('Loan Installment Paid')
                    ->view('emails.loan-paid');
    }
}
