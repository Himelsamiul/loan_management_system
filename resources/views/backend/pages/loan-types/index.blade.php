@extends('backend.master')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Create Loan Type</h3>

    {{-- Create Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            @if(session('success'))
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                </script>
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

    {{-- Loan Type List --}}
    <h3 class="mb-3">Loan Type List</h3>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Loan Name</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th width="25%">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($loanTypes as $key => $type)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $type->loan_name }}</td>
                            <td class="text-center">{{ $type->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($type->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center">

                                {{-- LOCK if used --}}
                                @if($type->is_used)
                                    <button class="btn btn-sm btn-secondary"
                                            disabled
                                            title="This loan type is used in {{ $type->used_count }} loan(s)">
                                        <i class="fas fa-lock"></i> Locked
                                    </button>

                                    <button class="btn btn-sm btn-warning" disabled>
                                        Edit
                                    </button>

                                    <button class="btn btn-sm btn-danger" disabled>
                                        Delete
                                    </button>

                                {{-- Editable if not used --}}
                                @else
                                    <button type="button"
                                            class="btn btn-sm btn-warning editBtn"
                                            data-url="{{ route('admin.loan.type.edit', $type->id) }}">
                                        Edit
                                    </button>

                                    <form id="delete-form-{{ $type->id }}"
                                          action="{{ route('admin.loan.type.delete', $type->id) }}"
                                          method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button type="button"
                                            class="btn btn-sm btn-danger deleteBtn"
                                            data-id="{{ $type->id }}">
                                        Delete
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No loan types found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/* DELETE CONFIRM */
document.querySelectorAll('.deleteBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        let id = this.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: 'This loan type will be permanently deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    });
});

/* EDIT CONFIRM */
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        let url = this.dataset.url;

        Swal.fire({
            title: 'Edit Loan Type?',
            text: 'Do you want to edit this loan type?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Yes, edit'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>
@endsection
