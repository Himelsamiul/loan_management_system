@extends('backend.master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Employees Access</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header bg-warning text-white">Update Employee</div>
        <div class="card-body">
            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Employee Dropdown --}}
                <div class="mb-3">
                    <label>Employee Name</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $role->employee_id == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Gmail --}}
                <div class="mb-3">
                    <label>Gmail</label>
                    <input type="email" name="gmail" class="form-control" value="{{ $role->gmail }}" required>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label>Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button class="btn btn-success">Update employee</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
