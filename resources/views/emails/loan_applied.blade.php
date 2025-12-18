<!DOCTYPE html>
<html>
<head>
    <title>Loan Application Submitted</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .header { background-color: #2c3e50; padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; text-transform: uppercase; }
        .content { padding: 30px; }
        .section-title { color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; text-transform: uppercase; font-size: 14px; font-weight: bold; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #fafafa; padding-bottom: 5px; }
        .label { font-weight: bold; color: #555; }
        .footer { background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 12px; color: #777; }
        .highlight { color: #27ae60; font-weight: bold; }

        /* Signature Styles */
        .signature-wrapper { margin-top: 30px; padding-top: 20px; border-top: 1px dashed #ddd; text-align: left; }
        .virtual-sign { 
            font-family: 'Brush Script MT', 'Cursive', sans-serif; 
            font-size: 26px; 
            color: #2c3e50; 
            display: block;
            margin-bottom: 0px;
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
            <h3>Hello {{ $loanData->name }} {{ $loanData->sure_name }},</h3>
            <p>Your loan application for <strong>{{ $loanData->loan_name->loan_name ?? 'N/A' }}</strong> has been submitted successfully.</p>

            <div class="section-title">Loan Details</div>

            <div class="detail-row">
                <span class="label">Loan Amount:</span>
                <span>{{ number_format($loanData->loan_amount, 2) }} BDT</span>
            </div>
            
            @php
                $interest = $loanData->loan_name->interest ?? 0;
                $totalAmount = $loanData->loan_amount + ($loanData->loan_amount * $interest / 100);
            @endphp

            <div class="detail-row">
                <span class="label">Total Amount (with interest):</span>
                <span class="highlight">{{ number_format($totalAmount, 2) }} BDT</span>
            </div>

            <div class="detail-row">
                <span class="label">Loan Duration:</span>
                <span>{{ $loanData->loan_duration }} months</span>
            </div>

            <p style="margin-top: 20px;">We will notify you once your application is approved.</p>

            <div class="signature-wrapper">
                <span class="virtual-sign">Nisha</span>
                <p class="sign-name">Sadia Sultana Nisha</p>
                <p class="sign-title">Authorized Officer, Ababil Finance Limited</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Ababil Finance Limited. All rights reserved.</p>
            <p>Mirpur-2, Dhaka, Bangladesh<br>
            Support: +880 1310-439446 | Email: info@ababilfinance.com<br>
            Website: www.ababilfinance.com</p>
            <p style="font-size: 10px; color: #aaa; margin-top: 15px;">This is an automated message. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
