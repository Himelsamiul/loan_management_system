@extends('frontend.master')

@section('content')
<div class="container py-5">

    <h3 class="mb-4">Installment History - {{ $loan->loan_name->loan_name ?? 'N/A' }}</h3>

    <p><strong>Loan Type:</strong> {{ $loan->loan_type->loan_name ?? 'N/A' }}</p>
    <p><strong>Total Payable (with interest):</strong> {{ number_format($totalAmount, 2) }} BDT</p>
    <p><strong>Duration:</strong> {{ $loan->loan_duration }} months</p>

    @php
        $totalInstallmentAmount = 0;
        $totalFineAmount = 0;
        $totalPaidAmount = 0;
    @endphp

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Month</th>
                <th>Due Date</th>
                <th>Grace Date</th>
                <th>Status</th>
                <th>Installment</th>
                <th>Fine</th>
                <th>Total (Installment + Fine)</th>
                <th>Paid Amount</th>
                <th>Paid Date</th>
            </tr>
        </thead>
        <tbody>
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

    {{-- ===================== SUMMARY ===================== --}}
    @php
        $grandTotal = $totalInstallmentAmount + $totalFineAmount;
        $remaining = $grandTotal - $totalPaidAmount;
    @endphp

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="mb-3">Summary</h5>
            <p><strong>Total Installment Amount:</strong> {{ number_format($totalInstallmentAmount, 2) }} BDT</p>
            <p><strong>Total Fine Amount:</strong> {{ number_format($totalFineAmount, 2) }} BDT</p>
            <p><strong>Grand Total (Installment + Fine):</strong> {{ number_format($grandTotal, 2) }} BDT</p>
            <hr>
            <p><strong>Total Paid:</strong> {{ number_format($totalPaidAmount, 2) }} BDT</p>
            <p><strong>Total Remaining:</strong> {{ number_format($remaining, 2) }} BDT</p>
        </div>
    </div>

    <a href="{{ route('profile.view') }}" class="btn btn-secondary mt-3">Back to Profile</a>
</div>
@endsection
