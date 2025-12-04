@extends('frontend.master')

@section('content')

<div class="container py-4">

    <h2 class="mb-3">Available Loan Names</h2>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>SL</th>
                <th>Loan Type</th>
                <th>Loan Name</th>
                <th>Interest (%)</th>
                <th>Status</th>
                <th>Apply</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loanNames as $key => $loan)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $loan->loanType->loan_name ?? 'N/A' }}</td>
                <td>{{ $loan->loan_name }}</td>
                <td>{{ $loan->interest }}%</td>
                <td>
                    <span class="badge bg-success">{{ ucfirst($loan->status) }}</span>
                </td>
                <td>
                    <a href="{{ route('frontend.loan.apply.form', $loan->id) }}" 
                       class="btn btn-primary btn-sm">
                        Apply for Loan
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection
