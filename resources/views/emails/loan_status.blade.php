<!DOCTYPE html>
<html>
<head>
    <title>Loan Application Status</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .header { background-color: #2c3e50; padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; text-transform: uppercase; }
        .content { padding: 30px; }
        .section-title { color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; text-transform: uppercase; font-size: 14px; font-weight: bold; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #fafafa; padding-bottom: 5px; }
        .label { font-weight: bold; color: #555; }
        .status-approved { color: #27ae60; font-weight: bold; }
        .status-rejected { color: #e74c3c; font-weight: bold; }

        /* Signature Styles */
        .signature-wrapper { margin-top: 30px; padding-top: 20px; border-top: 1px dashed #ddd; text-align: left; }
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
            <h3>Hello {{ $loan->name ?? $loan->user->name ?? 'Customer' }},</h3>

            <p>Your loan application for <strong>{{ $loan->loan_name->loan_name ?? 'N/A' }}</strong> has been <strong class="{{ $status == 'approved' ? 'status-approved' : 'status-rejected' }}">
                {{ ucfirst($status) }}</strong>.</p>

            @if($status == 'approved')
                <p>Congratulations! Your loan application has been approved. You can visit our office to finalize the loan process.</p>
            @else
                <p>We regret to inform you that your loan application has been rejected. Please contact our office for further assistance.</p>
            @endif

            <h4>Loan Details:</h4>
            <div class="detail-row">
                <span class="label">Loan Amount:</span>
                <span>{{ number_format($loan->loan_amount, 2) }} BDT</span>
            </div>

            @php
                $interest = $loan->loan_name->interest ?? 0;
                $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interest / 100);
            @endphp

            <div class="detail-row">
                <span class="label">Total Amount (with Interest):</span>
                <span>{{ number_format($totalAmount, 2) }} BDT</span>
            </div>
            <div class="detail-row">
                <span class="label">Loan Duration:</span>
                <span>{{ $loan->loan_duration }} months</span>
            </div>

            <div class="signature-wrapper">
                <!-- Thank you note above signature -->
                <p style="margin:0 0 5px 0; font-size:14px; color:#555;">Thank you for choosing Ababil Finance Limited.</p>
                <p style="margin:0 0 15px 0; font-size:14px; color:#555;">We appreciate your trust and cooperation.</p>

                <!-- Signature -->
                <span class="virtual-sign">Nisha</span>
                <p class="sign-name">Sadia Sultana Nisha</p>
                <p class="sign-title">Authorized Officer, Ababil Finance Limited</p>
            </div>
        </div>

        <div class="footer" style="background-color:#f4f4f4;padding:20px;text-align:center;font-size:12px;color:#777;">
            <p>&copy; {{ date('Y') }} Ababil Finance Limited. All rights reserved.</p>
            <p>Mirpur-2, Dhaka, Bangladesh<br>
            Support: +880 1310-439446 | Email: info@ababilfinance.com</p>
            <p style="font-size:10px;color:#aaa;margin-top:15px;">This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
