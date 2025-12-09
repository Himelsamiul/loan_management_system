@extends('backend.master')

@section('content')
<div class="container-fluid py-4"> {{-- Full width container --}}

    <h2 class="mb-3">All Loan Applications</h2>

    {{-- ======================== --}}
    {{-- SEARCH + FILTER SECTION --}}
    {{-- ======================== --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <strong>Search & Filter Loans</strong>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">

                {{-- Loan Type --}}
                <div class="col-md-2">
                    <label class="form-label">Loan Type</label>
                    <select name="loan_type_id" class="form-select">
                        <option value="">All</option>
                        @foreach($loanTypes as $type)
                        <option value="{{ $type->id }}" {{ request('loan_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->loan_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Loan Name --}}
                <div class="col-md-2">
                    <label class="form-label">Loan Name</label>
                    <select name="loan_name_id" class="form-select">
                        <option value="">All</option>
                        @foreach($loanNames as $name)
                        <option value="{{ $name->id }}" {{ request('loan_name_id') == $name->id ? 'selected' : '' }}>
                            {{ $name->loan_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Customer --}}
                <div class="col-md-2">
                    <label class="form-label">Customer Name</label>
                    <select name="customer_id" class="form-select">
                        <option value="">All</option>
                        @foreach($customers as $cus)
                        <option value="{{ $cus->id }}" {{ request('customer_id') == $cus->id ? 'selected' : '' }}>
                            {{ $cus->name }} ({{ $cus->mobile }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mobile --}}
                <div class="col-md-2">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control">
                </div>

                {{-- Status --}}
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="loan_given" {{ request('status') == 'loan_given' ? 'selected' : '' }}>Ongoing</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Search</button>
                </div>

            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ======================== --}}
    {{-- TABLE SECTION --}}
    {{-- ======================== --}}
    <div class="table-responsive"> {{-- Allows horizontal scroll on small screens --}}
        <table class="table table-bordered table-striped table-hover align-middle" style="min-width: 1500px;"> {{-- set min-width --}}
            <thead class="table-dark">
                <tr>
                    <th>SL</th>
                    <th>Loan Type</th>
                    <th>Loan Name</th>
                    <th>Full Name</th>
                    <th>Mobile</th>
                    <th>Father Name</th>
                    <th>Mother Name</th>
                    <th>NID Number</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Marital Status</th>
                    <th>Loan Amount</th>
                    <th>Loan Duration</th>
                    <th>Monthly Installment</th>
                    <th>Status</th>
                    <th>Present Address</th>
                    <th>Permanent Address</th>
                    <th>Documents</th>
                    <th>Applied At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $key => $app)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $app->loan_type->loan_name ?? 'N/A' }}</td>
                    <td>{{ $app->loan_name->loan_name ?? 'N/A' }}</td>
                    <td>{{ $app->name }}</td>
                    <td>{{ $app->user->mobile ?? 'N/A' }}</td>
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
                        $interestRate = $app->loan_name->interest ?? 0;
                        $totalAmount = $app->loan_amount + ($app->loan_amount * $interestRate / 100);
                        $monthlyInstallment = $app->loan_duration ? $totalAmount / $app->loan_duration : 0;
                        @endphp
                        {{ number_format($monthlyInstallment, 2) }}
                    </td>
                    <td>
                        @if($app->status=='pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($app->status=='approved')
                            <span class="badge bg-info">Approved</span>
                        @elseif($app->status=='loan_given')
                            <span class="badge bg-primary">Ongoing</span>
                        @elseif($app->status=='closed')
                            <span class="badge bg-success">Closed</span>
                        @elseif($app->status=='rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>{{ $app->present_address }}</td>
                    <td>{{ $app->permanent_address }}</td>
                    <td>
                        @for($i=1; $i<=5; $i++)
                            @php $docField="document$i"; @endphp
                            @if($app->$docField)
                                <a href="{{ asset('uploads/loan-documents/'.$app->$docField) }}" target="_blank">Doc {{ $i }}</a><br>
                            @endif
                        @endfor
                    </td>
                    <td>{{ $app->created_at->format('d M Y H:i') }}</td>
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
                    <td colspan="19" class="text-center">No applications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $applications->links() }}
    </div>
</div>

{{-- Optional CSS to reduce row height and wrap text --}}
<style>
    table td, table th {
        white-space: nowrap; /* Prevent wrapping */
        vertical-align: middle;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@endsection
