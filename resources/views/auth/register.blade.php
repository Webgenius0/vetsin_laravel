<!-- Registration is disabled for super admin. -->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    @include('backend.partials.style')
    <style>
        body { background: #f4f6fb; }
        .auth-card { border-radius: 18px; box-shadow: 0 8px 32px rgba(60,72,100,0.12); }
        .auth-logo { width: 60px; margin-bottom: 1rem; }
        .form-floating > label { left: 1.25rem; }
        .form-floating > .form-control { padding-left: 1.25rem; }
    </style>
</head>
<body>
    <div class="d-flex flex-column flex-root min-vh-100 justify-content-center align-items-center">
        <div class="auth-card bg-white p-4 p-md-5 w-100" style="max-width: 400px;">
            <div class="text-center mb-4">
                <img src="{{ asset($systemSetting->logo ?? 'backend/media/logos/logo-default.svg') }}" class="auth-logo" alt="Logo">
                <h2 class="fw-bold mb-2">Create Account</h2>
                <p class="text-muted mb-0">Sign up to get started!</p>
            </div>
            <form action="{{ route('register') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
                    <label for="name">Name</label>
                    @error('name')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    <label for="email">Email address</label>
                    @error('email')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    @error('password')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    <label for="password_confirmation">Confirm Password</label>
                    @error('password_confirmation')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Register</button>
            </form>
            <div class="text-center mt-4">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none">Login</a>
            </div>
        </div>
    </div>
    @include('backend.partials.script')
</body>
</html>
