@extends('backend.master')

@section('content')
<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-6">

            {{-- Page Header --}}
            <div class="mb-4 text-center">
                <h2 class="fw-bold">Edit Loan Type</h2>
                <p class="text-muted">Update the loan type and its status</p>
            </div>

            {{-- Card --}}
            <div class="card shadow-lg border-0 rounded-4 custom-card">
                <div class="card-body p-4">

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('admin.loan.type.update', $loanType->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Loan Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Loan Name</label>
                            <input type="text"
                                   name="loan_name"
                                   class="form-control form-control-lg custom-input"
                                   value="{{ $loanType->loan_name }}"
                                   {{ $loanType->is_used ? 'disabled' : 'required' }}>

                            @if($loanType->is_used)
                                 <small class="text-danger d-block mt-1">
                                    This loan type is already used. Only status can be changed.
                                </small>
                            @endif

                                @error('loan_name')
<small class="text-danger d-block fw-bold mt-1">
    {{ $message }} 
</small>

    @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select form-select-lg custom-input" required>
                                <option value="active" {{ $loanType->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $loanType->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.loan.type.index') }}" class="btn btn-outline-secondary btn-lg">Back</a>
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
    /* Card Styling */
    .custom-card {
        background: #ffffff;
        border-left: 5px solid #0d6efd; /* blue accent */
    }

    /* Input focus */
    .custom-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 5px rgba(13,110,253,0.3);
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
        const loanInput = document.querySelector('input[name="loan_name"]');
        const form = loanInput.closest('form');

        form.addEventListener('submit', function(e){
            if(!loanInput.disabled && loanInput.value.trim().length < 3){
                alert('Loan Name must be at least 3 characters long.');
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection
