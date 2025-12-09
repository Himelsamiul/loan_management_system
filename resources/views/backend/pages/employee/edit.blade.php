@extends('backend.master')

@section('content')
<div class="container py-4">
    <h2 class="mb-3">Edit Employee</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header bg-dark text-white">
            <strong>Edit Employee Details</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation', $employee->designation) }}">
                        @error('designation') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Role</label>
                        <input type="text" name="role" class="form-control" value="{{ old('role', $employee->role) }}">
                        @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $employee->address) }}">
                        @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="deactive" {{ $employee->status == 'deactive' ? 'selected' : '' }}>Deactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
