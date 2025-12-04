@extends('backend.master')
@section('content')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">All Registered Users</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
            @foreach($users as $key => $user)
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
            @endforeach
        </tbody>
    </table>
</div>
@endsection
