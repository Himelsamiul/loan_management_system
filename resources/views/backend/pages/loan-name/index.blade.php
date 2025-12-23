@extends('backend.master')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Create Loan Name</h3>

    {{-- Create Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.loan.name.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Loan Type</label>
                    <select name="loan_type_id" class="form-select" required>
                        <option value="">Select Loan Type</option>
                        @foreach($loanTypes as $type)
                            <option value="{{ $type->id }}" {{ old('loan_type_id')==$type->id?'selected':'' }}>
                                {{ $type->loan_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Loan Name</label>
                    <input type="text" name="loan_name" class="form-control" value="{{ old('loan_name') }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Interest (%)</label>
                    <input type="number" name="interest" class="form-control" min="0" step="0.01" value="{{ old('interest') }}" required>
                </div>

                <div class="col-md-2 mt-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-12 mt-2">
                    <button class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Search Field --}}
    <div class="mb-3">
        <input type="text" id="loanSearch" class="form-control form-control-lg shadow-sm" placeholder="🔍 Search Loan Name..." style="max-width:400px;">
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover" id="loanTable">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Loan Type</th>
                        <th>Loan Name</th>
                        <th>Interest (%)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loanNames as $key => $loan)
                        <tr class="text-center">
                            <td>{{ $key+1 }}</td>
                            <td>{{ $loan->loanType->loan_name }}</td>
                            <td class="loan-name">{{ $loan->loan_name }}</td>
                            <td>{{ $loan->interest }}</td>
                            <td>
                                @if($loan->status=='active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Status Toggle Button --}}
                                <button type="button" class="btn btn-sm {{ $loan->status=='active'?'btn-info':'btn-warning' }} statusBtn" data-id="{{ $loan->id }}">
                                    {{ ucfirst($loan->status) }}
                                </button>

                                @if($loan->applies()->count() > 0)
                                    <button type="button" class="btn btn-sm btn-secondary lockedBtn">
                                        <i class="bi bi-lock-fill"></i> Locked
                                    </button>
                                @else
                                    <a href="{{ route('admin.loan.name.edit', $loan->id) }}" class="btn btn-sm btn-warning editBtn">Edit</a>

                                    <form id="delete-form-{{ $loan->id }}" action="{{ route('admin.loan.name.delete', $loan->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="{{ $loan->id }}">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No Loan Names found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){

    // Client-side Search Filter
    const searchInput = document.getElementById('loanSearch');
    searchInput.addEventListener('keyup', function(){
        const filter = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('#loanTable tbody tr');

        rows.forEach(row => {
            const loanName = row.querySelector('.loan-name').textContent.toLowerCase();
            if(loanName.includes(filter)){
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // SweetAlert: Create & Update
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session("success") }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if(session('update_success'))
        Swal.fire({
            icon: 'success',
            title: 'Updated',
            text: '{{ session("update_success") }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    // Delete
    document.querySelectorAll('.deleteBtn').forEach(btn=>{
        btn.addEventListener('click',function(){
            let id = this.getAttribute('data-id');
            Swal.fire({
                title:"Are you sure?",
                text:"You want to delete this Loan Name",
                icon:"warning",
                showCancelButton:true,
                confirmButtonColor:"#3085d6",
                cancelButtonColor:"#d33",
                confirmButtonText:"Yes, delete it",
                cancelButtonText:"No"
            }).then((result)=>{
                if(result.isConfirmed){
                    document.getElementById('delete-form-'+id).submit();
                }
            })
        });
    });

    // Locked
    document.querySelectorAll('.lockedBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            Swal.fire({
                icon: 'info',
                title: 'Locked',
                text: 'This loan is already in use in Apply. Cannot edit or delete.',
                timer: 2500,
                showConfirmButton: false
            });
        });
    });

    // Edit
    document.querySelectorAll('.editBtn').forEach(btn=>{
        btn.addEventListener('click', function(e){
            e.preventDefault();
            let url = this.getAttribute('href');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to edit this loan name",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, edit it',
                cancelButtonText: 'Cancel'
            }).then((result)=>{
                if(result.isConfirmed){
                    window.location.href = url;
                }
            });
        });
    });

    // Status Toggle
    document.querySelectorAll('.statusBtn').forEach(btn=>{
        btn.addEventListener('click', function(){
            let id = this.getAttribute('data-id');
            let button = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change the status",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it',
                cancelButtonText: 'Cancel'
            }).then((result)=>{
                if(result.isConfirmed){
                    fetch("{{ url('admin/loan-name/status-toggle') }}/" + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success){
                            button.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                            if(data.status == 'active'){
                                button.classList.remove('btn-warning');
                                button.classList.add('btn-info');
                            } else {
                                button.classList.remove('btn-info');
                                button.classList.add('btn-warning');
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                                text: data.message,
                                timer: 3500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });
    });

});
</script>
@endsection
