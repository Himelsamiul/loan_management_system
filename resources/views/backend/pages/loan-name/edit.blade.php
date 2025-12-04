@extends('backend.master')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Edit Loan Name</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.loan.name.update', $loanName->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Loan Type</label>
                    <select name="loan_type_id" class="form-select" required>
                        <option value="">Select Loan Type</option>
                        @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}" {{ $loanName->loan_type_id == $type->id?'selected':'' }}>
                                {{ $type->loan_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Loan Name</label>
                    <input type="text" name="loan_name" class="form-control" value="{{ $loanName->loan_name }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Interest (%)</label>
                    <input type="number" name="interest" class="form-control" min="0" step="0.01" value="{{ $loanName->interest }}" required>
                </div>

                <div class="col-md-2 mt-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $loanName->status=='active'?'selected':'' }}>Active</option>
                        <option value="inactive" {{ $loanName->status=='inactive'?'selected':'' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-12 mt-2">
                    <button class="btn btn-success">Update</button>
                    <a href="{{ route('admin.loan.name.index') }}" class="btn btn-secondary">Back</a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
