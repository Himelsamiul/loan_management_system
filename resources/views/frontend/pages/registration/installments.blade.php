@extends('frontend.master')

@section('content')
<div class="container py-5">

    <h3 class="mb-4">Installment History - {{ $loan->loan_name->loan_name ?? 'N/A' }}</h3>

    <p><strong>Loan Type:</strong> {{ $loan->loan_type->loan_name ?? 'N/A' }}</p>
    <p><strong>Total Amount:</strong> {{ number_format($totalAmount, 2) }}</p>
    <p><strong>Duration:</strong> {{ $loan->loan_duration }} months</p>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Month #</th>
                <th>Due Date</th>
                <th>Installment Amount</th>
                <th>Paid Amount</th> {{-- New column --}}
                <th>Paid Date</th> {{-- New column --}}
            </tr>
        </thead>
        <tbody>
            @foreach($installments as $installment)
            <tr>
                <td>{{ $installment['month'] }}</td>
                <td>{{ $installment['due_date'] }}</td>
                <td>{{ $installment['amount'] }}</td>
                <td>{{ $installment['paid_amount'] ?? '-' }}</td> {{-- Placeholder for future --}}
                <td>{{ $installment['paid_date'] ?? '-' }}</td> {{-- Placeholder for future --}}
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('profile.view') }}" class="btn btn-secondary mt-3">Back to Profile</a>
</div>
@endsection
