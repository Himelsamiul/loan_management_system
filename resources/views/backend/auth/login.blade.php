<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Portal | Secure Access</title>
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #34495e;
      --accent-color: #3498db;
      --light-gray: #ecf0f1;
      --dark-gray: #7f8c8d;
      --error-color: #e74c3c;
      --success-color: rgb(46, 204, 125);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Roboto', 'Helvetica Neue', Arial, sans-serif;
      background: url('/admin2.AVIF') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: var(--primary-color);
      line-height: 1.6;
      position: relative;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0); /* Lighter overlay */
      z-index: 0;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.5); /* More opaque container */
      padding: 2.5rem;
      border-radius: 8px;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 1;
      border: 1px solid rgba(255, 255, 255, 0.61);
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 5px;
    }

    .login-title {
      text-align: center;
      margin-bottom: 2rem;
      position: relative;
    }

    .login-title h2 {
      color: var(--primary-color);
      font-size: 1.8rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
    }

    .login-title p {
      color: var(--dark-gray);
      font-size: 0.9rem;
    }

    .lock-icon {
      font-size: 1.8rem;
      color: var(--accent-color);
      margin-bottom: 1rem;
      display: inline-block;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-group input {
      width: 100%;
      padding: 0.9rem 2.5rem 0.9rem 1rem; /* Add right padding for eye icon */
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 0.95rem;
      background-color: white;
      transition: all 0.3s ease;
    }

    .form-group input:focus {
      border-color: var(--accent-color);
      outline: none;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }

    .form-group label {
      position: absolute;
      top: 0.9rem;
      left: 1rem;
      color: var(--dark-gray);
      pointer-events: none;
      transition: all 0.3s ease;
      background: white;
      padding: 0 0.3rem;
      font-size: 0.95rem;
    }

    .form-group input:focus + label,
    .form-group input:not(:placeholder-shown) + label {
      top: -0.6rem;
      font-size: 0.75rem;
      color: var(--accent-color);
    }

    /* Eye icon styling */
    .form-group .toggle-password {
      position: absolute;
      top: 50%;
      right: 1rem;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--dark-gray);
      font-size: 1.2rem;
      user-select: none;
    }

    button {
      width: 100%;
      padding: 0.9rem;
      background-color: var(--primary-color);
      color: white;
      font-size: 1rem;
      font-weight: 500;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-top: 0.5rem;
    }

    button:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .remember-me {
      display: flex;
      align-items: center;
      margin: 1.5rem 0;
    }

    .remember-me input {
      margin-right: 0.5rem;
      accent-color: var(--accent-color);
    }

    .remember-me label {
      color: var(--dark);
      font-size: 0.9rem;
    }

    .error-message {
      color: var(--error-color);
      font-size: 0.85rem;
      text-align: center;
      margin-bottom: 1.5rem;
      padding: 0.8rem;
      background-color: rgba(231, 76, 60, 0.1);
      border-radius: 4px;
      border-left: 3px solid var(--error-color);
    }

    .error-message ul {
      list-style-type: none;
    }

    .error-message li {
      margin-bottom: 0.3rem;
    }

    .footer-links {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.85rem;
    }

    .footer-links a {
      color: var(--primary-color);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: var(--error-color);
      text-decoration: underline;
    }

    .footer-links span {
      color: var(--dark-gray);
      margin: 0 0.5rem;
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 1.5rem;
        margin: 0 1rem;
      }

      .login-title h2 {
        font-size: 1.5rem;
      }

      body {
        background-attachment: scroll;
      }
    }
  </style>

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>

  <div class="login-container">
    <div class="login-title">
      <i class="fas fa-lock lock-icon" id="lockIcon"></i>
      <h2>Administrator Portal</h2>
      <p style="color: var(--secondary-color);">Secure access to management dashboard</p>
    </div>

    @if ($errors->any())
      <div class="error-message">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="post">
      @csrf

      <div class="form-group">
        <input type="email" name="email" id="email" required placeholder=" " />
        <label for="email">Email Address</label>
      </div>

      <div class="form-group">
        <input type="password" name="password" id="password" required placeholder=" " />
        <label for="password">Password</label>
        <!-- Eye icon for toggle -->
        <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>
      </div>

      <div class="remember-me">
        <input type="checkbox" name="remember" id="remember" />
        <label for="remember">Keep me signed in</label>
      </div>

      <button type="submit">Sign In</button>
    </form>

    <div class="footer-links">
      <a href="#" style="font-weight: bold;">Forgot password?</a>
      <span>•</span>
      <a href="#" style="font-weight: bold;">Contact support</a>
    </div>
  </div>

  <script>
    const passwordField = document.getElementById('password');
    const lockIcon = document.getElementById('lockIcon');
    const togglePassword = document.getElementById('togglePassword');

    passwordField.addEventListener('focus', () => {
      lockIcon.style.transform = 'scale(1.1)';
      lockIcon.style.color = '#e74c3c';
    });

    passwordField.addEventListener('blur', () => {
      lockIcon.style.transform = 'scale(1)';
      lockIcon.style.color = '#3498db';
    });

    passwordField.addEventListener('input', () => {
      lockIcon.style.animation = 'shake 0.3s';
      setTimeout(() => {
        lockIcon.style.animation = '';
      }, 300);
    });

    togglePassword.addEventListener('click', () => {
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      togglePassword.classList.toggle('fa-eye');
      togglePassword.classList.toggle('fa-eye-slash');
    });

    // Add shake animation to styles
    const style = document.createElement('style');
    style.textContent = `
      @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-3px); }
        40% { transform: translateX(3px); }
        60% { transform: translateX(-3px); }
        80% { transform: translateX(3px); }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>
</html>