@extends('backend.master')
@section('content')
<div class="container py-5">

    <h3 class="mb-4 text-primary text-center fw-bold">All Registered Users</h3>

    {{-- Search / Filter --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #0d6efd, #6610f2);">
            <strong>Search & Filter Users</strong>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Search by Name">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Mobile</label>
                    <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control" placeholder="Search by Mobile">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="text" name="email" value="{{ request('email') }}" class="form-control" placeholder="Search by Email">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-gradient w-100" style="background: linear-gradient(90deg, #6610f2, #0d6efd); color:#fff; font-weight:600; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        Search
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background: linear-gradient(90deg, #0d6efd, #6610f2); color: #fff;" class="text-center">
                    <tr>
                        <th>SL</th>
                        <th>Name</th>
                        <th>Sure Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>DOB</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $key => $user)
                        <tr class="text-center table-light" style="transition: all 0.3s; cursor:pointer;" onmouseover="this.style.backgroundColor='#f0f8ff'" onmouseout="this.style.backgroundColor=''">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->sure_name }}</td>
                            <td>{{ $user->mobile }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->date_of_birth }}</td>
                            <td>
                                @if($user->applies()->count() > 0)
                                    <button type="button" class="btn btn-sm btn-secondary lockedBtn" title="User has applied">
                                        <i class="bi bi-lock-fill"></i> Locked
                                    </button>
                                @else
                                    <form action="{{ route('admin.registration.delete', $user->id) }}" method="POST" class="d-inline deleteForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="{{ $user->id }}">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $users->links() }}
    </div>

</div>

{{-- SweetAlert JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){

    // Delete confirmation
    document.querySelectorAll('.deleteBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this user",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result)=>{
                if(result.isConfirmed){
                    this.closest('form').submit();
                }
            });
        });
    });

    // Locked notification
    document.querySelectorAll('.lockedBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            Swal.fire({
                icon: 'info',
                title: 'Locked',
                text: 'This user has applied for a loan. Cannot delete.',
                timer: 2500,
                showConfirmButton: false
            });
        });
    });

});
</script>

{{-- Additional CSS --}}
<style>
    .table-hover tbody tr:hover {
        transform: scale(1.02);
        background-color: #e9f5ff !important;
    }

    .btn-gradient:hover {
        opacity: 0.9;
    }
</style>
@endsection
