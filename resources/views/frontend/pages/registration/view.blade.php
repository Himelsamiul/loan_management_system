@extends('frontend.master')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">My Profile</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }} {{ $user->sure_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Mobile:</strong> {{ $user->mobile }}</p>
            <p><strong>Date of Birth:</strong> {{ $user->date_of_birth }}</p>
            <p><strong>Address:</strong> {{ $user->address }}</p>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-2">Edit Profile</a>
        </div>
    </div>

    <h4 class="mb-3">Loan Application History</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SL</th>
                <th>Loan Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
           
        </tbody>
    </table>
</div>
@endsection
