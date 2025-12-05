@extends('backend.master')

@section('content')
<div class="container py-4">

    <h2 class="mb-3">Given Loans List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Loan Type</th>
                <th>Loan Name</th>
                <th>Amount</th>
                <th>Interest (%)</th>
                <th>Total Amount</th>
                <th>Duration (months)</th>
                <th>Monthly Installment</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($givenLoans as $key => $loan)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $loan->name }}</td>
                <td>{{ $loan->loan_type->loan_name ?? 'N/A' }}</td>
                <td>{{ $loan->loan_name->loan_name ?? 'N/A' }}</td>
                <td>{{ number_format($loan->loan_amount, 2) }}</td>

                @php
                    $interestRate = $loan->loan_name->interest ?? 0;
                    $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
                    $monthlyInstallment = $loan->loan_duration ? $totalAmount / $loan->loan_duration : 0;
                @endphp

                <td>{{ $interestRate }}%</td>
                <td>{{ number_format($totalAmount, 2) }}</td>
                <td>{{ $loan->loan_duration }} months</td>
                <td>{{ number_format($monthlyInstallment, 2) }}</td>
                <td>
                    <span class="badge bg-primary">Given</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-muted">No given loans found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
