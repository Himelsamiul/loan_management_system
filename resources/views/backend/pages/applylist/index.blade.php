@extends('backend.master')

@section('content')
<div class="container-fluid py-4">

    <h2 class="mb-3 text-center text-primary fw-bold">All Loan Applications</h2>

    {{-- ======================== --}}
    {{-- SEARCH + FILTER SECTION --}}
    {{-- ======================== --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #0d6efd, #6610f2);">
            <strong>Search & Filter Loans</strong>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">

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

                <div class="col-md-2">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control">
                </div>

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
                    <button class="btn btn-gradient w-100" style="background: linear-gradient(90deg, #6610f2, #0d6efd); color:#fff; font-weight:600;">Search</button>
                </div>

            </form>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false
        });
    </script>
    @endif

    {{-- ======================== --}}
    {{-- TABLE SECTION --}}
    {{-- ======================== --}}
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>SL</th>
                    <th>Loan Type</th>
                    <th>Loan Name</th>
                    <th>Applicant Name</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Applied At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $key => $app)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $app->loan_type->loan_name ?? 'N/A' }}</td>
                    <td>{{ $app->loan_name->loan_name ?? 'N/A' }}</td>
                    <td>{{ $app->name }}</td>
                    <td>{{ $app->user->mobile ?? 'N/A' }}</td>
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
                    <td>{{ $app->created_at->format('d M Y H:i') }}</td>
<td>
    {{-- Approve button --}}
    @if($app->status == 'pending')
        <form action="{{ route('admin.loan.updateStatus', $app->id) }}" method="POST" style="display:inline-block;" class="statusForm">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="approved">
            <button type="submit" class="btn btn-sm btn-success approveBtn">Approve</button>
        </form>

        {{-- Reject button --}}
        <form action="{{ route('admin.loan.updateStatus', $app->id) }}" method="POST" style="display:inline-block;" class="statusForm">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="rejected">
            <button type="submit" class="btn btn-sm btn-danger rejectBtn">Reject</button>
        </form>
    @else
        <span class="text-muted">Action Done</span>
    @endif

    {{-- Full Details button --}}
    <a href="{{ route('admin.loan.fullshow', $app->id) }}" class="btn btn-sm btn-primary mt-1">Full Details</a>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">No applications found.</td>
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

{{-- SweetAlert Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Optional CSS --}}
<style>
.table-hover tbody tr:hover {
    transform: scale(1.01);
    background-color: #f0f8ff !important;
    transition: all 0.3s;
}

.btn-gradient:hover {
    opacity: 0.9;
}
</style>
@endsection
