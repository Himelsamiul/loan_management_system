@extends('backend.master')
@section('content')
<div class="container py-4">
    <h3 class="mb-3">All Registered Users</h3>

    {{-- Success message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Search / Filter --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <strong>Search & Filter Users</strong>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">

                {{-- Name --}}
                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Search by Name">
                </div>

                {{-- Mobile --}}
                <div class="col-md-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="mobile" value="{{ request('mobile') }}" class="form-control" placeholder="Search by Mobile">
                </div>

                {{-- Email --}}
                <div class="col-md-3">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" value="{{ request('email') }}" class="form-control" placeholder="Search by Email">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Search</button>
                </div>

            </form>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
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
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->sure_name }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->address }}</td>
                    <td>{{ $user->date_of_birth }}</td>
                    <td>
                        <form action="{{ route('admin.registration.delete', $user->id) }}" method="POST" onsubmit="return confirm('Delete user?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection
