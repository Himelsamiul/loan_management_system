@extends('frontend.master')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Loan Application History</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>SL</th>
                <th>Applicant Name</th>
                <th>Loan Type</th>
                <th>Loan Name</th>
                <th>Loan Amount</th>
                <th>Loan Duration (months)</th>
                <th>Monthly Installment</th>
                <th>Status</th>
                <th>Applied At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $key => $app)
              @php
                    $interestAmount = $app->loan_amount * ($app->loan_name->interest / 100);
                    $totalAmount = $app->loan_amount + $interestAmount;
                    $monthlyInstallment = $totalAmount / $app->loan_duration;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $app->name }}</td>
                    <td>{{ $app->loan_type->loan_name ?? 'N/A' }}</td>
                    <td>{{ $app->loan_name->loan_name ?? 'N/A' }} ({{ $app->loan_name->interest ?? 0 }}%)</td>
                    <td>{{ number_format($app->loan_amount, 2) }}</td>
                    <td>{{ $app->loan_duration }}</td>
                    <td>{{ number_format($monthlyInstallment, 2) }}</td>
                    <td>
                        @if($app->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($app->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>{{ $app->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No applications found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
