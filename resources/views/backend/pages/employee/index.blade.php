@extends('backend.master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Employee Management</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ===== Create Employee Form ===== --}}
    <div class="card mb-4 shadow-sm border-primary">
        <div class="card-header bg-primary text-white">
            <strong>Create New Employee</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.employees.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="id_card_number" class="form-label">ID Card Number</label>
                        <input type="text" name="id_card_number" id="id_card_number" class="form-control" value="{{ old('id_card_number') }}" required>
                        @error('id_card_number')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">Employee Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="designation" class="form-label">Designation</label>
                        <input type="text" name="designation" id="designation" class="form-control" value="{{ old('designation') }}" required>
                        @error('designation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" name="role" id="role" class="form-control" value="{{ old('role') }}" required>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" required>
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" required>
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-success mt-2">Add Employee</button>
            </form>
        </div>
    </div>

    {{-- ===== Search Input ===== --}}
    <div class="mb-3" style="max-width:400px;">
        <input type="text" id="employeeSearch" class="form-control form-control-lg shadow-sm" placeholder="🔍 Search by Name...">
    </div>

    {{-- ===== Employees Table ===== --}}
    <div class="card shadow-sm border-secondary">
        <div class="card-header bg-secondary text-white">
            <strong>All Employees</strong>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="employeesTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>ID Card</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $key => $employee)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $employee->id_card_number }}</td>
                            <td class="employee-name">{{ $employee->name }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>{{ $employee->role }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->address }}</td>
                            <td>
                                <span class="badge {{ $employee->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</button>
                                </form>
                                <form action="{{ route('admin.employees.toggleStatus', $employee->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        {{ $employee->status == 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== AJAX-Like Client-Side Search ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('employeeSearch');
    const tableRows = document.querySelectorAll('#employeesTable tbody tr');

    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const nameCell = row.querySelector('.employee-name');
            if (nameCell) {
                const text = nameCell.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            }
        });
    });
});
</script>
@endsection
