@extends('frontend.master')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body {
        background-color: #f5f7fa;
    }

    .registration-form {
        max-width: 700px;
        margin: 30px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .registration-form h3 {
        text-align: center;
        margin-bottom: 25px;
        color: #0d6efd;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }

    button.btn-primary {
        background-color: #0d6efd;
        border: none;
    }

    button.btn-primary:hover {
        background-color: #0b5ed7;
    }
</style>

<div class="container">
    <div class="registration-form">
        <h3>User Registration</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="registerForm" action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Sure Name *</label>
                    <input type="text" name="sure_name" class="form-control" placeholder="Enter your sure name" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Mobile Number *</label>
                    <input type="text" name="mobile" class="form-control" placeholder="01XXXXXXXXX" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email Address *</label>
                    <input type="email" name="email" class="form-control" placeholder="example@mail.com" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Date of Birth *</label>
                    <input type="date" id="dob" name="date_of_birth" class="form-control" max="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Address *</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="Enter your address" required></textarea>
                </div>

                <hr>

                <div class="col-md-6 mb-3">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>

                <div class="col-md-6 mb-4">
                    <label>Re-enter Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required>
                </div>

                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary px-5">Register</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault(); // prevent immediate submission

        Swal.fire({
            title: 'Check your information?',
            text: "Are you sure all the information is correct?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                this.submit();

                // Optional: Show a success message after form submission
                Swal.fire({
                    title: 'Registered!',
                    text: 'Your registration was successful.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });
</script>
@endsection
