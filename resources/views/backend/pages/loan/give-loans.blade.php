@extends('backend.master')

@section('content')
<div class="container py-4">

    <h2 class="mb-3">Given Loans List</h2>

    {{-- ======================== --}}
    {{-- SEARCH + FILTER SECTION --}}
    {{-- ======================== --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <strong>Search & Filter Given Loans</strong>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">

                {{-- Loan Type --}}
                <div class="col-md-3">
                    <label class="form-label">Loan Type</label>
                    <select name="loan_type_id" class="form-select">
                        <option value="">All</option>
                        @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ request('loan_type_id') == $type->id ? 'selected' : '' }}>
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
                            <option value="{{ $name->id }}"
                                {{ request('loan_name_id') == $name->id ? 'selected' : '' }}>
                                {{ $name->loan_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Customer --}}
                <div class="col-md-3">
                    <label class="form-label">Customer</label>
                    <select name="user_id" class="form-select">
                        <option value="">All</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->mobile }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Search</button>
                    <a href="{{ route('admin.loan.given') }}" class="btn btn-secondary">Refresh</a>
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
                    <th>Customer</th>
                    <th>Loan Type</th>
                    <th>Loan Name</th>
                    <th>Amount</th>
                    <th>Interest (%)</th>
                    <th>Total Amount</th>
                    <th>Duration</th>
                    <th>Monthly Installment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($givenLoans as $key => $loan)
                    @php
                        $interestRate = $loan->loan_name->interest ?? 0;
                        $totalAmount = $loan->loan_amount + ($loan->loan_amount * $interestRate / 100);
                        $monthlyInstallment = $loan->loan_duration
                            ? $totalAmount / $loan->loan_duration
                            : 0;
                    @endphp
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $loan->user->name ?? 'N/A' }}</td>
                        <td>{{ $loan->loan_type->loan_name ?? 'N/A' }}</td>
                        <td>{{ $loan->loan_name->loan_name ?? 'N/A' }}</td>
                        <td>{{ number_format($loan->loan_amount, 2) }}</td>
                        <td>{{ $interestRate }}%</td>
                        <td>{{ number_format($totalAmount, 2) }}</td>
                        <td>{{ $loan->loan_duration }} months</td>
                        <td>{{ number_format($monthlyInstallment, 2) }}</td>

                        {{-- STATUS --}}
                        <td>
                            @if($loan->status === 'loan_given')
                                <span class="badge bg-primary">Given</span>
                            @elseif($loan->status === 'closed')
                                <span class="badge bg-success">Closed</span>
                            @else
                                <span class="badge bg-secondary">
                                    {{ ucfirst(str_replace('_',' ', $loan->status)) }}
                                </span>
                            @endif
                        </td>

                        {{-- ACTION --}}
                        <td>
                            <a href="{{ route('admin.loan.details', $loan->id) }}"
                               class="btn btn-sm btn-info">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted">
                            No given loans found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ======================== --}}
{{-- OPTIONAL CSS --}}
{{-- ======================== --}}
<style>
    table th, table td {
        white-space: nowrap;
        vertical-align: middle;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@endsection
