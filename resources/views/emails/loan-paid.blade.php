<!DOCTYPE html>
<html>
<head>
    <title>Loan Installment Paid</title>
</head>
<body>
    <h3>Hello {{ $loan->name }} {{ $loan->sure_name }},</h3>

    <p>Your installment for <strong>{{ $loan->loan_name->loan_name ?? 'Loan' }}</strong> has been paid successfully.</p>

    <hr>

    <h4>Installment Details</h4>
    <p><strong>Month:</strong> {{ $installment['month'] }} of {{ $loan->loan_duration }}</p>
    <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($installment['due_date'] ?? now())->format('d M Y') }}</p>
    <p><strong>Installment Amount:</strong> {{ number_format($installment['amount'], 2) }} BDT</p>
    <p><strong>Fine:</strong> {{ number_format($installment['fine'], 2) }} BDT</p>
    <p><strong>Total Paid This Month:</strong> {{ number_format($installment['amount'] + $installment['fine'], 2) }} BDT</p>

    <hr>

    <h4>Payment Summary</h4>
    @php
        $interestRate = $loan->loan_name->interest ?? 0;
        $totalLoanAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
        $totalPaid = $loan->paid_amount;
        $remaining = $totalLoanAmount - $totalPaid;
        $remainingInstallments = $loan->loan_duration - $loan->paid_installments;
    @endphp

    <p><strong>Total Loan Amount:</strong> {{ number_format($totalLoanAmount, 2) }} BDT</p>
    <p><strong>Total Paid So Far:</strong> {{ number_format($totalPaid, 2) }} BDT</p>
    <p><strong>Remaining Amount:</strong> {{ number_format($remaining, 2) }} BDT</p>
    <p><strong>Installments Paid:</strong> {{ $loan->paid_installments }} of {{ $loan->loan_duration }}</p>
    <p><strong>Remaining Installments:</strong> {{ $remainingInstallments }}</p>

    <hr>

    <p>Thank you for your payment. We appreciate your trust in Ababil Finance Company.</p>
</body>
</html>
