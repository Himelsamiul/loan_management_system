@extends('frontend.master')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        .login-box {
            width: 400px;
            margin: 50px auto;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            font-family: Arial;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group { margin-bottom: 15px; }
        .form-group label { font-weight: bold; }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            background: #1e73be;
            border: none;
            color: #fff;
            border-radius: 6px;
            margin-top: 10px;
            cursor: pointer;
        }
        .info-text {
            margin-top: 10px;
            text-align: center;
            color: #444;
        }
        .small-text {
            text-align: center;
            margin-top: 5px;
            color: #777;
            font-size: 14px;
        }
        .remember-box {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>

</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    @if ($errors->any())
        <p style="color:red;">{{ $errors->first() }}</p>
    @endif

    <form action="{{ route('login.submit') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="remember-box">
            <input type="checkbox" name="remember">
            <label>Save login info</label>
        </div>

        <button class="btn-login">Login</button>

      <p class="info-text">
    Don’t have account? 
    <a href="{{ route('register.create') }}">Registration</a>
</p>
        <p class="small-text">Forgot Password?</p>

    </form>
</div>

</body>
</html>

@endsection