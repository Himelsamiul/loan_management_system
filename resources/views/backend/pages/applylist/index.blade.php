@extends('backend.master')

@section('content')
<div class="container py-4">

    <h2 class="mb-3">All Loan Applications</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>SL</th>
                <th>Loan Type</th>
                <th>Loan Name</th>
                <th>Full Name</th>
                <th>Father Name</th>
                <th>Mother Name</th>
                <th>NID Number</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Marital Status</th>
                <th>Loan Amount</th>
                <th>Loan Duration (months)</th>
                <th>Monthly Installment</th>
                <th>Status</th>
                <th>Present Address</th>
                <th>Permanent Address</th>
                <th>Documents</th>
                <th>Applied At</th>
                <th>Actions</th> {{-- New column for approve/reject --}}
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $key => $app)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $app->loan_type->loan_name ?? 'N/A' }}</td>
                <td>{{ $app->loan_name->loan_name ?? 'N/A' }}</td>
                <td>{{ $app->name }}</td>
                <td>{{ $app->father_name }}</td>
                <td>{{ $app->mother_name }}</td>
                <td>{{ $app->nid_number }}</td>
                <td>{{ \Carbon\Carbon::parse($app->date_of_birth)->format('d M Y') }}</td>
                <td>{{ ucfirst($app->gender) }}</td>
                <td>{{ ucfirst($app->marital_status) }}</td>
                <td>{{ number_format($app->loan_amount, 2) }}</td>
                <td>{{ $app->loan_duration }}</td>

                <td>
    @php
        $interestRate = $app->loan_name->interest ?? 0; // % interest
        $totalAmount = $app->loan_amount + ($app->loan_amount * $interestRate / 100);
        $monthlyInstallment = $app->loan_duration ? $totalAmount / $app->loan_duration : 0;
    @endphp
    {{ number_format($monthlyInstallment, 2) }}
</td>
    
                <td>
                    @if($app->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($app->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
                <td>{{ $app->present_address }}</td>
                <td>{{ $app->permanent_address }}</td>
                <td>
                    @for($i=1; $i<=5; $i++)
                        @php $docField = "document$i"; @endphp
                        @if($app->$docField)
                            <a href="{{ asset('uploads/loan-documents/'.$app->$docField) }}" target="_blank">Doc {{ $i }}</a><br>
                        @endif
                    @endfor
                </td>
                <td>{{ $app->created_at->format('d M Y H:i') }}</td>

                {{-- Actions --}}
                <td>
                    @if($app->status == 'pending')
                        <form action="{{ route('admin.loan.updateStatus', $app->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>

                        <form action="{{ route('admin.loan.updateStatus', $app->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    @else
                        <span class="text-muted">Action Done</span>
                    @endif
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="18" class="text-center">No applications found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
