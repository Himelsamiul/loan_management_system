<!DOCTYPE html>
<html>
<head>
    <title>Loan Installment Receipt</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .header { background-color: #2c3e50; padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; text-transform: uppercase; }
        .content { padding: 30px; }
        .section-title { color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; text-transform: uppercase; font-size: 14px; font-weight: bold; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #fafafa; padding-bottom: 5px; }
        .label { font-weight: bold; color: #555; }
        .summary-box { background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin-top: 20px; border-left: 4px solid #2c3e50; }
        .footer { background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #777; }
        .highlight { color: #27ae60; font-weight: bold; }
        
        /* Signature Styles */
        .signature-wrapper { margin-top: 30px; padding-top: 20px; border-top: 1px dashed #ddd; }
        .virtual-sign { 
            font-family: 'Brush Script MT', 'Cursive', sans-serif; 
            font-size: 26px; 
            color: #2c3e50; 
            margin-bottom: 0px;
            display: block;
        }
        .sign-name { font-weight: bold; margin: 0; color: #333; }
        .sign-title { font-size: 13px; color: #777; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ABABIL FINANCE LIMITED</h1>
        </div>

        <div class="content">
            <h3>Hello {{ $loan->name }} {{ $loan->sure_name }},</h3>
            <p>Your installment for <strong>{{ $loan->loan_name->loan_name ?? 'Loan' }}</strong> has been processed successfully. Below is your payment receipt.</p>

            <div class="section-title">Installment Details</div>
            <div class="detail-row">
                <span class="label">Month:</span>
                <span>{{ $installment['month'] }} of {{ $loan->loan_duration }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Due Date:</span>
                <span>{{ \Carbon\Carbon::parse($installment['due_date'] ?? now())->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Installment Amount:</span>
                <span>{{ number_format($installment['amount'], 2) }} BDT</span>
            </div>
            <div class="detail-row">
                <span class="label">Fine / Penalty:</span>
                <span>{{ number_format($installment['fine'], 2) }} BDT</span>
            </div>
            <div class="detail-row" style="font-size: 1.1em;">
                <span class="label">Total Paid This Month:</span>
                <span class="highlight">{{ number_format($installment['amount'] + $installment['fine'], 2) }} BDT</span>
            </div>

            @php
                $interestRate = $loan->loan_name->interest ?? 0;
                $totalLoanAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
                $totalPaid = $loan->paid_amount;
                $remaining = $totalLoanAmount - $totalPaid;
                $remainingInstallments = $loan->loan_duration - $loan->paid_installments;
            @endphp

            <div class="summary-box">
                <div class="section-title" style="border: none; margin-bottom: 10px;">Loan Summary</div>
                <div class="detail-row">
                    <span class="label">Total Loan (Incl. Interest):</span>
                    <span>{{ number_format($totalLoanAmount, 2) }} BDT</span>
                </div>
                <div class="detail-row">
                    <span class="label">Total Paid So Far:</span>
                    <span>{{ number_format($totalPaid, 2) }} BDT</span>
                </div>
                <div class="detail-row">
                    <span class="label">Remaining Balance:</span>
                    <span style="color: #e74c3c; font-weight: bold;">{{ number_format($remaining, 2) }} BDT</span>
                </div>
                <div class="detail-row">
                    <span class="label">Remaining Installments:</span>
                    <span>{{ $remainingInstallments }} Months</span>
                </div>
            </div>

            <p style="margin-top: 30px;">Thank you for your timely payment. We appreciate your continued trust in <strong>Ababil Finance Limited</strong>.</p>

            <div class="signature-wrapper">
                <span class="virtual-sign">Nisha</span>
                <p class="sign-name">Sadia Sultana Nisha</p>
                <p class="sign-title">Authorized Officer, Ababil Finance Limited</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Ababil Finance Limited. All rights reserved.</p>
            <p>Mirpur-1, Dhaka, Bangladesh<br>
            Support: +880 1310-439446 | Email: ababilfinance@gmail.com</p>
            <p style="font-size: 10px; color: #aaa; margin-top: 15px;">This is an automated transaction receipt. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>