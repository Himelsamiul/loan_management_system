@extends('backend.master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Employees Access</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ===== Create Role ===== --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Add Employee Access</div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Employee Name</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Gmail</label>
                    <input type="email" name="gmail" class="form-control" required>
                </div>
                <div class="mb-3 position-relative">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    <span class="toggle-password" style="position:absolute; top:38px; right:10px; cursor:pointer;">
                        👁️
                    </span>
                </div>
                <div class="mb-3 position-relative">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                    <span class="toggle-password" style="position:absolute; top:38px; right:10px; cursor:pointer;">
                        👁️
                    </span>
                </div>
                <button class="btn btn-success">Create Employee Access</button>
            </form>
        </div>
    </div>

    {{-- ===== Search Filter ===== --}}
    <div class="mb-3" style="max-width:400px;">
        <input type="text" id="roleSearch" class="form-control form-control-lg shadow-sm" placeholder="🔍 Search by Employee Name...">
    </div>

    {{-- ===== Roles Table ===== --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="rolesTable">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Gmail</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td class="employee-name">{{ $role->employee->name ?? 'N/A' }}</td>
                        <td>{{ $role->gmail }}</td>
                        <td>{{ $role->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ===== JS for Search & Password Toggle ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== AJAX-like search filter =====
    const searchInput = document.getElementById('roleSearch');
    const tableRows = document.querySelectorAll('#rolesTable tbody tr');

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

    // ===== Password toggle =====
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if(input.type === 'password') {
                input.type = 'text';
                this.textContent = '🙈';
            } else {
                input.type = 'password';
                this.textContent = '👁️';
            }
        });
    });
});
</script>
@endsection
