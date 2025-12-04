@extends('frontend.master')
@section('content')
<div class="container py-5">
    <h3 class="mb-4">User Registration</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Name *</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Sure Name *</label>
                <input type="text" name="sure_name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Mobile Number *</label>
                <input type="text" name="mobile" class="form-control" required>
            </div>
<div class="col-md-6 mb-3">
    <label>Email Address *</label>
    <input type="email" name="email" class="form-control" required>
</div>

            <div class="col-md-6 mb-3">
                <label>Date of Birth *</label>
                <input type="date" name="date_of_birth" class="form-control" required>
            </div>

            <div class="col-md-12 mb-3">
                <label>Address *</label>
                <textarea name="address" class="form-control" rows="2" required></textarea>
            </div>

            <hr>

            <div class="col-md-6 mb-3">
                <label>Password *</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6 mb-4">
                <label>Re-enter Password *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-primary px-4">Register</button>
            </div>

        </div>
    </form>
</div>
@endsection
