@extends('backend.master')

@section('content')
<div class="container py-4" style="animation: fadeInPage 1s ease-in;">
    <h2 class="mb-4 text-primary">Employees Access</h2>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    {{-- ===== Add Employee Access Form ===== --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">Add Employee Access</div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="fw-semibold">Employee Name</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            @if(!\App\Models\Role::where('employee_id', $employee->id)->exists())
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Gmail</label>
                    <input type="email" name="gmail" class="form-control" required>
                </div>
                <div class="mb-3 position-relative">
                    <label class="fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                    <span class="toggle-password" style="position:absolute; top:38px; right:10px; cursor:pointer;">👁️</span>
                </div>
                <div class="mb-3 position-relative">
                    <label class="fw-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                    <span class="toggle-password" style="position:absolute; top:38px; right:10px; cursor:pointer;">👁️</span>
                </div>
                <button class="btn btn-success fw-bold">Create Employee Access</button>
            </form>
        </div>
    </div>

    {{-- ===== Search Filter ===== --}}
    <div class="mb-3" style="max-width:400px;">
        <input type="text" id="roleSearch" class="form-control form-control-lg shadow-sm" placeholder="🔍 Search by Employee Name...">
    </div>

    {{-- ===== Employee Roles Table ===== --}}
    <div class="table-responsive mb-5 shadow-sm">
        <h4 class="mb-3 text-secondary">Employee Roles</h4>
        <table class="table table-bordered table-striped align-middle" id="rolesTable">
            <thead class="table-primary text-dark">
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Gmail</th>
                    <th>Created At</th>
                    <th>Status</th>
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
                        {{-- Status Button --}}
                        <td>
                            <form action="{{ route('admin.roles.toggleStatus', $role->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @if($role->status === 'active')
                                    <button type="submit" class="btn btn-success btn-sm btn-status">Active</button>
                                @else
                                    <button type="submit" class="btn btn-danger btn-sm btn-status">Inactive</button>
                                @endif
                            </form>
                        </td>
                        {{-- Action Buttons --}}
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

    {{-- ===== Super Admins Table ===== --}}
    <div class="table-responsive shadow-lg p-3 mb-5 bg-white rounded" style="animation: fadeInSuperAdmin 1.5s ease-in;">
        <h4 class="mb-3 text-warning fw-bold">Super Admins</h4>
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-white">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::all() as $admin)
                    <tr class="table-highlight">
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>Super Admin</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ===== JS for Search & Password Toggle ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== Search Filter =====
    const searchInput = document.getElementById('roleSearch');
    const tableRows = document.querySelectorAll('#rolesTable tbody tr');

    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            const nameCell = row.querySelector('.employee-name');
            row.style.display = nameCell.textContent.toLowerCase().includes(filter) ? '' : 'none';
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

{{-- ===== Minimal CSS for Status Button & Animations ===== --}}
<style>
.btn-status {
    width: 90px;
    font-weight: bold;
    border-radius: 50px;
    transition: all 0.3s;
}
.btn-status:hover {
    transform: scale(1.05);
    opacity: 0.9;
    cursor: pointer;
}

/* Fade-in animation for the whole page */
@keyframes fadeInPage {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Fade-in animation for Super Admin section */
@keyframes fadeInSuperAdmin {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Highlight Super Admin rows */
.table-highlight {
    background-color: #fff3cd !important;
    font-weight: 600;
    transition: background 0.5s ease;
}
.table-highlight:hover {
    background-color: #ffeeba !important;
}

/* Rounded table headers for a smoother look */
table th {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}
</style>
@endsection
