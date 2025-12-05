@extends('backend.master')

@section('content')
<div class="container py-4">
    <h2>Loan Details - {{ $loan->name }}</h2>

    <p><strong>Loan Name:</strong> {{ $loan->loan_name->loan_name ?? 'N/A' }}</p>
    <p><strong>Loan Type:</strong> {{ $loan->loan_type->loan_name ?? 'N/A' }}</p>
    <p><strong>Total Amount:</strong> {{ number_format($totalAmount,2) }}</p>
    <p><strong>Duration:</strong> {{ $loan->loan_duration }} months</p>

    <h4 class="mt-4">Installment Schedule</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Month #</th>
                <th>Due Date</th>
                <th>Installment Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($installments as $installment)
                <tr>
                    <td>{{ $installment['month'] }}</td>
                    <td>{{ $installment['due_date'] }}</td>
                    <td>{{ $installment['amount'] }}</td>
                    <td>{{ $installment['status'] }}</td>
                    <td>
                        @if($installment['status'] == 'Pending')
                            <button class="btn btn-sm btn-success">Mark Paid</button>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
