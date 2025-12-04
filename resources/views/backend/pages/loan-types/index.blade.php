@extends('backend.master')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Create Loan Type</h3>

    {{-- Create Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.loan.type.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Loan Name</label>
                    <input type="text" name="loan_name" class="form-control" placeholder="Enter loan name" required>
                    @error('loan_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <input type="hidden" name="status" value="active">

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Filters --}}
    <h3 class="mb-3">Loan Type List</h3>
    <div class="card shadow-sm mb-3 p-3">
        <form method="GET" action="{{ route('admin.loan.type.index') }}" class="row g-3 align-items-center">

            <div class="col-md-4">
                <label class="form-label text-primary fw-bold">From Date</label>
                <input type="text" name="from_date" id="from_date" class="form-control flatpickr"
                       placeholder="Select start date" value="{{ request('from_date') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label text-primary fw-bold">To Date</label>
                <input type="text" name="to_date" id="to_date" class="form-control flatpickr"
                       placeholder="Select end date" value="{{ request('to_date') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label text-primary fw-bold">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-success w-50 align-self-end">Filter</button>
                <a href="{{ route('admin.loan.type.index') }}" class="btn btn-secondary w-50 align-self-end">Reset</a>
            </div>

        </form>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Loan Name</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th width="15%">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($loanTypes as $key => $type)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $type->loan_name }}</td>
                            <td class="text-center">{{ $type->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($type->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.loan.type.edit', $type->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form id="delete-form-{{ $type->id }}" 
                                      action="{{ route('admin.loan.type.delete', $type->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="{{ $type->id }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No loan types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d",
        allowInput: true
    });
</script>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', function () {
        let id = this.getAttribute('data-id');

        Swal.fire({
            title: "Are you sure?",
            text: "You want to delete this Loan Type",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    });
});
</script>
@endsection
