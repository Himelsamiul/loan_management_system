@extends('backend.master')

@section('content')
<div class="container py-4">

    <h2 class="mb-3">Approved Loan List (Give Loan)</h2>

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
                <div class="col-md-3">
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
                <div class="col-md-3">
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

                {{-- Registered User --}}
                <div class="col-md-3">
                    <label class="form-label">Customer</label>
                    <select name="user_id" class="form-select">
                        <option value="">All</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->mobile }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Search + Refresh --}}
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="{{ route('admin.loan.approved') }}" class="btn btn-secondary">Refresh</a>
                </div>

            </form>
        </div>
    </div>

    {{-- ======================== --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ======================== --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ======================== --}}
    {{-- TABLE SECTION --}}
    {{-- ======================== --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
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
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($approvedLoans as $key => $loan)
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
                        <span class="badge bg-success">Approved</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.loan.give', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Give Loan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">No approved loans found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Optional CSS --}}
<style>
    table td, table th {
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@endsection
