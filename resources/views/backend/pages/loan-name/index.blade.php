@extends('backend.master')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Create Loan Name</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

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

    {{-- Filters --}}
    <div class="card shadow-sm mb-3 p-3">
        <form method="GET" action="{{ route('admin.loan.name.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Loan Type</label>
                <select name="loan_type_id" class="form-select">
                    <option value="">All</option>
                    @foreach($loanTypes as $type)
                        <option value="{{ $type->id }}" {{ request('loan_type_id')==$type->id?'selected':'' }}>
                            {{ $type->loan_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">From</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="form-label">To</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-success w-50">Filter</button>
                <a href="{{ route('admin.loan.name.index') }}" class="btn btn-secondary w-50">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
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
                            <td>{{ $loan->loan_name }}</td>
                            <td>{{ $loan->interest }}</td>
                            <td>
                                @if($loan->status=='active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.loan.name.edit', $loan->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form id="delete-form-{{ $loan->id }}" action="{{ route('admin.loan.name.delete', $loan->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-sm btn-danger deleteBtn" data-id="{{ $loan->id }}">
                                    Delete
                                </button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
    })
})
</script>
@endsection
