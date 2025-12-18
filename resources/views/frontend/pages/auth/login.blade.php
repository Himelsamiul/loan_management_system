@extends('frontend.master')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #f2f5f8ff, #5dade2);
    }

    .login-wrapper {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 420px;
        background: #fff;
        padding: 35px;
        border-radius: 15px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .login-card h2 {
        text-align: center;
        font-weight: 700;
        color: #1e73be;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #1e73be;
        box-shadow: 0 0 0 3px rgba(30,115,190,.15);
        outline: none;
    }

    .remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .remember-row label {
        margin: 0;
        cursor: pointer;
    }

    .btn-login {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(to right, #1e73be, #3498db);
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30,115,190,.4);
    }

    .extra-links {
        text-align: center;
        margin-top: 18px;
        font-size: 14px;
    }

    .extra-links a {
        color: #1e73be;
        font-weight: 600;
        text-decoration: none;
    }

    .extra-links a:hover {
        text-decoration: underline;
    }

    .error-text {
        color: red;
        font-size: 14px;
        margin-bottom: 10px;
        text-align: center;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Welcome Back</h2>

        @if ($errors->any())
            <div class="error-text">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                <a href="#">Forgot Password?</a>
            </div>

            <button class="btn-login">Login</button>
        </form>

        <div class="extra-links">
            Don’t have an account?
            <a href="{{ route('register.create') }}">Register Now</a>
        </div>
    </div>
</div>
@endsection
