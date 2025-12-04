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
                <th>Present Address</th>
                <th>Permanent Address</th>
                <th>Documents</th>
                <th>Applied At</th>
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
                <td>{{ $app->present_address }}</td>
                <td>{{ $app->permanent_address }}</td>
                <td>
                    @for($i=1; $i<=5; $i++)
                        @php
                            $docField = "document$i";
                        @endphp
                        @if($app->$docField)
                            <a href="{{ asset('uploads/loan-documents/'.$app->$docField) }}" target="_blank">
                                Doc {{ $i }}
                            </a><br>
                        @endif
                    @endfor
                </td>
                <td>{{ $app->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="15" class="text-center">No applications found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
