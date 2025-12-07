@extends('backend.master')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">Loan Details</h2>

    {{-- Borrower Information --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Borrower Information</h5>
            <p><strong>Name:</strong> {{ $loan->name }}</p>
            <p><strong>Loan Type:</strong> {{ $loan->loan_type->loan_name ?? 'N/A' }}</p>
            <p><strong>Loan Name:</strong> {{ $loan->loan_name->loan_name ?? 'N/A' }}</p>
            <p><strong>Loan Amount:</strong> {{ number_format($loan->loan_amount, 2) }} BDT</p>
            <p><strong>Total Payable (with interest):</strong> {{ number_format($totalAmount, 2) }} BDT</p>
            <p><strong>Duration:</strong> {{ $loan->loan_duration }} months</p>
            <p><strong>Loan Given Date:</strong> {{ $loan->updated_at->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Installment Schedule --}}
    <h4>Installment Schedule</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Month</th>
                <th>Due Date</th>
                <th>Due Date (with Grace)</th>
                <th>Installment Amount</th>
                <th>Status</th>
                <th>Fine</th>
                <th>Total (Installment + Fine)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotal = 0;
                $totalFine = 0;
                $totalInstallments = 0;
                $totalPaid = 0; // future use
            @endphp

            @foreach($installments as $ins)
                @php
                    $totalWithFine = $ins['amount'] + $ins['fine'];
                    $grandTotal += $totalWithFine;
                    $totalFine += $ins['fine'];
                    $totalInstallments += $ins['amount'];
                @endphp
                <tr>
                    <td>{{ $ins['month'] }}</td>
                    <td>{{ $ins['due_date'] }}</td>
                    <td>{{ $ins['due_date_grace'] }}</td>
                    <td>{{ number_format($ins['amount'], 2) }} BDT</td>
                    <td>
                        @if($ins['status'] == 'Upcoming')
                            <span class="badge bg-info">Upcoming</span>
                        @elseif($ins['status'] == 'Grace Period')
                            <span class="badge bg-warning">Grace Period</span>
                        @else
                            <span class="badge bg-danger">Late</span>
                        @endif
                    </td>
                    <td>{{ number_format($ins['fine'], 2) }} BDT</td>
                    <td>{{ number_format($totalWithFine, 2) }} BDT</td>
                    <td>
                        {{-- Pay Loan button --}}
                        <form action="" method="POST">
                            @csrf
                            <input type="hidden" name="installment_month" value="{{ $ins['month'] }}">
                            <input type="hidden" name="installment_amount" value="{{ $ins['amount'] }}">
                            <input type="hidden" name="fine_amount" value="{{ $ins['fine'] }}">
                            <button type="submit" class="btn btn-sm btn-success">Pay Loan</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            <tr class="table-success">
                <td colspan="6" class="text-end"><strong>Grand Total (With Fines)</strong></td>
                <td colspan="2"><strong>{{ number_format($grandTotal, 2) }} BDT</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- ========================= --}}
    {{--    FINAL SUMMARY BOX      --}}
    {{-- ========================= --}}
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            <strong>Loan Summary</strong>
        </div>
        <div class="card-body">

            <p><strong>Total Installment Amount:</strong> {{ number_format($totalInstallments, 2) }} BDT</p>
            <p><strong>Total Fine:</strong> {{ number_format($totalFine, 2) }} BDT</p>
            <p><strong>Total Payable (Installments + Fines):</strong> {{ number_format($grandTotal, 2) }} BDT</p>

            {{-- Future paid calculation --}}
            @php
                $remaining = $grandTotal - $totalPaid;
            @endphp

            <p><strong>Total Paid Amount:</strong> {{ number_format($totalPaid, 2) }} BDT</p>
            <p><strong>Total Remaining Amount:</strong> {{ number_format($remaining, 2) }} BDT</p>
        </div>
    </div>

    <a href="" class="btn btn-secondary mt-3">Back to Loan List</a>
</div>
@endsection
