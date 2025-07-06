<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
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
        <div class="auth-card bg-white p-5 p-md-5 w-100 " style="max-width: 500px;">
            <div class="text-center mb-4">
                <img src="{{ asset($systemSetting->logo ?? 'backend/media/logos/logo-default.svg') }}" class="auth-logo" alt="Logo">
                <h2 class="fw-bold mb-2">Admin Login</h2>
            </div>
            <form action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <div class="form-floating mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
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
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">Log In</button>
            </form>
        </div>
    </div>
    @include('backend.partials.script')
</body>

</html>
