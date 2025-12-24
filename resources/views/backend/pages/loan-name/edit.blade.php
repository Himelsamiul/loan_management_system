@extends('backend.master')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Page Header --}}
            <div class="mb-4 text-center">
                <h2 class="fw-bold">Edit Loan Name</h2>
                <p class="text-muted">Update the loan details and status here</p>
            </div>

            {{-- Card --}}
            <div class="card shadow-lg border-0 rounded-4 custom-card">
                <div class="card-body p-4">

                    {{-- Form --}}
                    <form id="loanForm" action="{{ route('admin.loan.name.update', $loanName->id) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        {{-- Loan Type --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Loan Type</label>
                            <select name="loan_type_id" class="form-select form-select-lg custom-input" required>
                                <option value="">Select Loan Type</option>
                                @foreach($loanTypes as $type)
                                    <option value="{{ $type->id }}" {{ $loanName->loan_type_id == $type->id ? 'selected' : '' }}>
                                        {{ $type->loan_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
<div class="col-md-4">
    <label class="form-label">Loan Name</label>
    <input type="text" name="loan_name" class="form-control" value="{{ old('loan_name', $loanName->loan_name ?? '') }}" required>

    {{-- Error message --}}
    @if($errors->has('loan_name'))
        <small class="text-danger fw-bold">{{ $errors->first('loan_name') }}</small>
    @endif
</div>


                        {{-- Interest --}}
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Interest (%)</label>
                            <input type="number" name="interest" class="form-control form-control-lg custom-input" min="0" step="0.01" value="{{ $loanName->interest }}" required>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4 mt-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select form-select-lg custom-input">
                                <option value="active" {{ $loanName->status=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ $loanName->status=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.loan.name.index') }}" class="btn btn-outline-secondary btn-lg">Back</a>
                            <button type="submit" class="btn btn-success btn-lg btn-hover">Update</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

{{-- Custom CSS --}}
@push('styles')
<style>
    /* Card styling */
    .custom-card {
        background: #ffffff;
        border-left: 5px solid #198754; /* green accent */
    }

    /* Input focus */
    .custom-input:focus {
        border-color: #198754;
        box-shadow: 0 0 5px rgba(25,135,84,0.5);
    }

    /* Buttons hover effect */
    .btn-hover {
        transition: all 0.3s ease;
    }
    .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* Header subtitle */
    .text-muted {
        font-size: 0.95rem;
    }
</style>
@endpush

{{-- Custom JS --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('loanForm');

        form.addEventListener('submit', function(e){
            const loanName = form.loan_name.value.trim();
            const interest = parseFloat(form.interest.value);

            if(loanName.length < 3){
                alert('Loan Name must be at least 3 characters long.');
                e.preventDefault();
            } else if(isNaN(interest) || interest < 0){
                alert('Interest must be a valid positive number.');
                e.preventDefault();
            }
        });
    });
</script>
@endpush

@endsection
