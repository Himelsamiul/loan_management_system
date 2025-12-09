<!DOCTYPE html>
<html>
<head>
    <title>Loan Installment Paid</title>
</head>
<body>
    <h3>Hello {{ $loan->name }},</h3>
    <p>Your installment for <strong>{{ $loan->loan_name->loan_name ?? 'Loan' }}</strong> has been paid successfully.</p>

    <p><strong>Month:</strong> {{ $installment['month'] }}</p>
    <p><strong>Amount:</strong> {{ number_format($installment['amount'], 2) }} BDT</p>
    <p><strong>Fine:</strong> {{ number_format($installment['fine'], 2) }} BDT</p>
    <p><strong>Total Paid:</strong> {{ number_format($installment['amount'] + $installment['fine'], 2) }} BDT</p>

    <p>Thank you for your payment.</p>
</body>
</html>
