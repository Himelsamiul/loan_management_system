@extends('backend.master')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Edit Loan Type</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.loan.type.update', $loanType->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Loan Name --}}
<input type="text"
       name="loan_name"
       class="form-control"
       value="{{ $loanType->loan_name }}"
       {{ $loanType->is_used ? 'disabled' : 'required' }}>


@if($loanType->is_used)
    <small class="text-muted">
        This loan type is already used. Only status can be changed.
    </small>
@endif

                </div>

                {{-- Status Dropdown --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ $loanType->status == 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ $loanType->status == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button class="btn btn-primary px-4">Update</button>
                <a href="{{ route('admin.loan.type.index') }}" class="btn btn-secondary px-4">Back</a>
            </form>

        </div>
    </div>

</div>
@endsection
