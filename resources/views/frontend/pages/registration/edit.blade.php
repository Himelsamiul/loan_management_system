@extends('frontend.master')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Edit Profile</h3>

    <form action="{{ route('profile.update') }}" method="POST" id="profile-form">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name *</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>Sure Name *</label>
                <input type="text" name="sure_name" class="form-control" value="{{ $user->sure_name }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>Mobile Number *</label>
                <input type="text" name="mobile" class="form-control" value="{{ $user->mobile }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>Email Address *</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label>Date of Birth *</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ $user->date_of_birth }}" readonly>
            </div>

            <div class="col-md-12 mb-3">
                <label>Address *</label>
                <textarea name="address" class="form-control" rows="3" required>{{ $user->address }}</textarea>
            </div>

            <div class="col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-success px-4">Update Profile</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Updated Successfully!',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif

<script>
    // SweetAlert confirmation before submitting the form
    document.getElementById('profile-form').addEventListener('submit', function(e) {
        e.preventDefault(); // prevent default submit
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update your address?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // submit the form
            }
        });
    });
</script>
@endsection
