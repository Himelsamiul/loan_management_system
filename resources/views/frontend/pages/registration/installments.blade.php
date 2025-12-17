@extends('frontend.master')

@section('content')

<style>
    body {
        background-color: #f0f4f8;
    }

    /* Invoice Container */
    #invoice {
        max-width: 210mm; /* A4 width */
        min-height: 297mm; /* A4 height */
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        font-family: 'Arial', sans-serif;
    }

    #invoice h3, #invoice h5 {
        color: #0d6efd;
        font-weight: 600;
    }

    #invoice table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 14px;
    }

    #invoice table th, #invoice table td {
        border: 1px solid #ccc;
        padding: 8px 10px;
        text-align: center;
    }

    #invoice table th {
        background-color: #f2f2f2;
    }

    .summary-card {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        font-size: 14px;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 12px;
    }

    .btn-print {
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: 600;
        margin-top: 20px;
    }

    /* Print Styles */
 @media print {
    body {
        background: #fff;
    }

    /* Hide everything except the invoice container */
    nav, footer, .btn, .btn-print, .topbar {
        display: none !important;
    }

    #invoice {
        box-shadow: none;
        border: none;
        margin: 0;
        padding: 0;
    }
}

</style>

<div class="container py-4">
    <div id="invoice">

        <!-- Header -->
        <div class="text-center mb-4">
            <h3>Loan Installment Slip</h3>
            <p><strong>{{ $loan->loan_name->loan_name ?? 'N/A' }}</strong></p>
            <p>Loan Type: {{ $loan->loan_type->loan_name ?? 'N/A' }} | Duration: {{ $loan->loan_duration }} months</p>
            <p>Total Payable (with interest): {{ number_format($totalAmount, 2) }} BDT</p>
        </div>

        <!-- Installment Table -->
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Due Date</th>
                    <th>Grace Date</th>
                    <th>Status</th>
                    <th>Installment</th>
                    <th>Fine</th>
                    <th>Total</th>
                    <th>Paid Amount</th>
                    <th>Paid Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalInstallmentAmount = 0;
                    $totalFineAmount = 0;
                    $totalPaidAmount = 0;
                @endphp
                @foreach($installments as $ins)
                    @php
                        $rowTotal = $ins['amount'] + $ins['fine'];
                        $totalInstallmentAmount += $ins['amount'];
                        $totalFineAmount += $ins['fine'];
                        $totalPaidAmount += $ins['paid_amount'];
                    @endphp
                    <tr>
                        <td>{{ $ins['month'] }}</td>
                        <td>{{ $ins['due_date'] }}</td>
                        <td>{{ $ins['due_date_grace'] }}</td>
                        <td>
                            @if($ins['status'] == 'Upcoming')
                                <span class="badge bg-info">Upcoming</span>
                            @elseif($ins['status'] == 'Grace Period')
                                <span class="badge bg-warning">Grace Period</span>
                            @else
                                <span class="badge bg-danger">Late</span>
                            @endif
                        </td>
                        <td>{{ number_format($ins['amount'], 2) }} BDT</td>
                        <td>{{ number_format($ins['fine'], 2) }} BDT</td>
                        <td>{{ number_format($rowTotal, 2) }} BDT</td>
                        <td>{{ number_format($ins['paid_amount'], 2) }} BDT</td>
                        <td>{{ $ins['paid_date'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        @php
            $grandTotal = $totalInstallmentAmount + $totalFineAmount;
            $remaining = $grandTotal - $totalPaidAmount;
        @endphp
        <div class="summary-card">
            <h5>Summary</h5>
            <p><strong>Total Installment Amount:</strong> {{ number_format($totalInstallmentAmount, 2) }} BDT</p>
            <p><strong>Total Fine Amount:</strong> {{ number_format($totalFineAmount, 2) }} BDT</p>
            <p><strong>Grand Total:</strong> {{ number_format($grandTotal, 2) }} BDT</p>
            <hr>
            <p><strong>Total Paid:</strong> {{ number_format($totalPaidAmount, 2) }} BDT</p>
            <p><strong>Total Remaining:</strong> {{ number_format($remaining, 2) }} BDT</p>
        </div>

        <!-- Print Button -->
        <div class="text-center">
            <button class="btn btn-info btn-print" onclick="window.print()">Print Slip</button>
        </div>
    </div>
</div>

@endsection
