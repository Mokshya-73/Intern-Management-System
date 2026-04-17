<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Internship Management Login</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Google Font: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    /* Your custom styles here (as provided) */
    body {
      font-family: "Poppins", sans-serif;
      background-image: url("https://i.postimg.cc/PJhqHWxx/loginBG.jpg");
       background-repeat: no-repeat;
      background-size: cover;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 1rem;
    }
    .login-container {
      background: #fff;
      border-radius: 15px;
      padding: 30px 25px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    }
    .login-container h2 {
      font-size: 1.3rem;
      font-weight: 600;
      text-align: center;
      margin-bottom: 5px;
    }
    .login-container h4 {
      font-size: 1.3rem;
      text-align: center;
      margin-bottom: 25px;
      font-weight: 600;
    }
    .form-control {
      border-radius: 10px;
      font-weight: 500;
      font-size: 0.7rem;
    }
    .btn-login {
      background-color: #3f3fbf;
      color: #fff;
      border-radius: 20px;
    }
    .btn-login:hover {
      background-color: #09a418;
      color: #fff;
    }
    .remember-label {
      font-weight: 500;
      font-size: 0.7rem;
    }
    .btn-google {
      border: 1px solid #ccc;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-weight: 500;
    }
    .google-icon {
      width: 20px;
    }
    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 20px 0;
      font-weight: 300;
      font-size: 0.9rem;
    }
    .divider::before, .divider::after {
      content: "";
      flex: 1;
      border-bottom: 1px solid #ccc;
    }
    .divider:not(:empty)::before {
      margin-right: 0.75em;
    }
    .divider:not(:empty)::after {
      margin-left: 0.75em;
    }
    .remember-me {
      display: flex;
      align-items: center;
      gap: 5px;
      margin-top: 10px;
    }
    .slt-logo {
      display: block;
      margin: 25px auto 0;
      width: 120px;
    }
    .password-label, .login-label {
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #707375;
      font-weight: 500;
      font-size: 0.9rem;
    }
    .toggle-password {
      font-size: 0.85rem;
      color: #6c757d;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    .toggle-password i {
      font-size: 1rem;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Internship Management System</h2>
    <h4>Log In</h4>

    {{-- Display Validation Errors --}}
    @if($errors->any())
      <div class="alert alert-danger py-1 text-center">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3">
        <div class="login-label">
          <label for="loginID" class="form-label">Login ID</label>
        </div>
        <input
          type="text"
          name="identity"
          class="form-control"
          id="loginID"
          placeholder="Enter email or reg no"
          value="{{ old('identity') }}"
        />
      </div>

      <div class="mb-2">
        <div class="password-label">
          <label for="password" class="form-label mb-0">Password</label>
          <span id="togglePassword" class="toggle-password">
            <i class="bi bi-eye-slash" id="eyeIcon"></i> Hide
          </span>
        </div>
        <input
          type="password"
          name="password"
          class="form-control"
          id="password"
          placeholder="Enter password"
        />
      </div>

      <div class="remember-me">
        <input type="checkbox" id="rememberMe" name="remember" checked />
        <label for="rememberMe" class="remember-label mb-0">Remember me</label>
      </div>

      <div class="d-grid mt-3">
        <button type="submit" class="btn btn-login">Log In</button>
      </div>
    </form>

    <div class="divider">OR</div>

    <a href="{{ route('google.login', ['mode' => 'login']) }}" class="btn btn-google w-100 mb-3">
      <img
        src="https://developers.google.com/identity/images/g-logo.png"
        alt="Google"
        class="google-icon"
      />
      Continue with Google
    </a>

    <img
      src="https://i.postimg.cc/QCkgQS5p/SLTMobitel-Logo-svg.png"
      alt="SLTMobitel Logo"
      class="slt-logo"
    />
  </div>

  <!-- Password Toggle Script -->
  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", function () {
      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      eyeIcon.className =
        type === "password" ? "bi bi-eye-slash" : "bi bi-eye";
      togglePassword.lastChild.textContent =
        type === "password" ? " Hide" : " Show";
    });
  </script>
</body>
</html>
