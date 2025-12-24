@extends('backend.master')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .edit-container {
        max-width: 900px;
        margin: 40px auto;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .card-header {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: none;
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .btn-secondary {
        transition: all 0.3s ease;
    }

    .alert-success {
        border-left: 5px solid #0d6efd;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
</style>

<div class="container edit-container">
    <h2 class="mb-4 text-center text-primary">Edit Employee</h2>

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
                        <label class="form-label">Department</label>
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

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
