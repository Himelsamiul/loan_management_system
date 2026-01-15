@extends('frontend.master')

@section('content')
<div class="container py-5">

    <h3 class="mb-4">My Profile</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }} {{ $user->sure_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Mobile:</strong> {{ $user->mobile }}</p>
            <p><strong>Date of Birth:</strong> {{ $user->date_of_birth }}</p>
            <p><strong>Address:</strong> {{ $user->address }}</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-2">Edit Profile</a>
        </div>
    </div>

    <h4 class="mb-3">Loan Application History</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Applicant Name</th>
                    <th>Loan Type</th>
                    <th>Loan Name</th>
                    <th>Amount</th>
                    <th>Profit (%)</th>
                    <th>Total Amount</th>
                    <th>Duration (months)</th>
                    <th>Monthly Installment</th>
                    <th>Status</th>
                    <th>Applied At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $key => $app)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $app->name }}</td>
                    <td>{{ $app->loan_type->loan_name ?? 'N/A' }}</td>
                    <td>{{ $app->loan_name->loan_name ?? 'N/A' }}</td>
                    <td>{{ number_format($app->loan_amount, 2) }}</td>

                    @php
                        $interestRate = $app->loan_name->interest ?? 0;
                        $totalAmount = $app->loan_amount + ($app->loan_amount * $interestRate / 100);
                        $monthlyInstallment = $app->loan_duration ? $totalAmount / $app->loan_duration : 0;
                    @endphp

                    <td>{{ $interestRate }}%</td>
                    <td>{{ number_format($totalAmount, 2) }}</td>
                    <td>{{ $app->loan_duration }} months</td>
                    <td>{{ number_format($monthlyInstallment, 2) }}</td>

                    {{-- Updated Status --}}
                    <td>
                        @if($app->status_label == 'Pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($app->status_label == 'Approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($app->status_label == 'Given')
                            <span class="badge bg-primary">Given</span>
                        @elseif($app->status_label == 'Closed')
                            <span class="badge bg-success">Closed</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>

                    <td>{{ $app->created_at->format('d M Y H:i') }}</td>

                    <td>
                        @if(in_array($app->status_label, ['Given', 'Closed']))
                            <a href="{{ route('frontend.loan.installments', $app->id) }}" class="btn btn-sm btn-info">
                                View Installment History
                            </a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center text-muted">No loan applications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
