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
    <label class="form-label">Loan Type</label>
    <input type="text" name="loan_name" class="form-control" placeholder="Enter loan type" value="{{ old('loan_name') }}" required>

    {{-- Error message below input --}}
    @if($errors->has('loan_name'))
        <small class="text-danger" style="font-weight:bold;">{{ $errors->first('loan_name') }}</small>

    @endif
</div>


                <input type="hidden" name="status" value="active">

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Search Field --}}
    <div class="mb-3">
        <input type="text" id="loanTypeSearch" class="form-control form-control-lg shadow-sm" placeholder="🔍 Search Loan Type..." style="max-width:400px;">
    </div>

    {{-- Loan Type List --}}
    <h3 class="mb-3">Loan Type List</h3>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover table-bordered align-middle" id="loanTypeTable">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Loan Type</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th width="25%">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($loanTypes as $key => $type)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="loan-name">{{ $type->loan_name }}</td>
                            <td class="text-center">{{ $type->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($type->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>

                            <td class="text-center">

                                {{-- EDIT LOGIC --}}
                                <button type="button"
                                        class="btn btn-sm btn-warning editBtn"
                                        data-url="{{ route('admin.loan.type.edit', $type->id) }}"
                                        title="{{ $type->is_used ? 'This loan type is used. Only status can be changed.' : '' }}">
                                    Edit
                                </button>

                                {{-- DELETE LOGIC --}}
                                @if($type->is_used)
                                    <button type="button" 
                                            class="btn btn-sm btn-secondary lockedDeleteBtn"
                                            title="This loan type is used. Cannot delete.">
                                        Delete
                                    </button>
                                @else
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

{{-- SweetAlert + Search JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Client-side Search Filter
const searchInput = document.getElementById('loanTypeSearch');
searchInput.addEventListener('keyup', function(){
    const filter = searchInput.value.toLowerCase();
    const rows = document.querySelectorAll('#loanTypeTable tbody tr');

    rows.forEach(row => {
        const loanName = row.querySelector('.loan-name').textContent.toLowerCase();
        if(loanName.includes(filter)){
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// DELETE CONFIRM FOR USABLE ITEMS
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

// EDIT CONFIRM
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

// LOCKED DELETE BUTTON ALERT
document.querySelectorAll('.lockedDeleteBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        Swal.fire({
            icon: 'info',
            title: 'Cannot Delete',
            text: "You can't delete this loan type because it is already used in a loan. You can only edit its status.",
            confirmButtonColor: '#3085d6',
        });
    });
});
</script>
@endsection
