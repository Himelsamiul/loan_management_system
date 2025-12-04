@extends('frontend.master')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Edit Profile</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name *</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Sure Name *</label>
                <input type="text" name="sure_name" class="form-control" value="{{ $user->sure_name }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Mobile Number *</label>
                <input type="text" name="mobile" class="form-control" value="{{ $user->mobile }}" required>
            </div>
<div class="col-md-6 mb-3">
    <label>Email Address *</label>
    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
</div>

            <div class="col-md-6 mb-3">
                <label>Date of Birth *</label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ $user->date_of_birth }}" required>
            </div>

            <div class="col-md-12 mb-3">
                <label>Address *</label>
                <textarea name="address" class="form-control" rows="2" required>{{ $user->address }}</textarea>
            </div>

            <div class="col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-success px-4">Update Profile</button>
            </div>

        </div>
    </form>
</div>
@endsection
