<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>AuthSecure</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #f5f5f0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.auth-card {
    background: #fff;
    border: 0.5px solid rgba(0,0,0,0.1);
    border-radius: 16px;
    padding: 2rem;
    width: 100%;
    max-width: 400px;
}
.brand-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #534AB7;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-purple {
    background: #534AB7;
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    height: 42px;
}
.btn-purple:hover { background: #3C3489; color: #fff; }
.btn-social {
    border: 0.5px solid rgba(0,0,0,0.15);
    border-radius: 10px;
    background: #fff;
    color: #333;
    font-size: 13px;
    font-weight: 500;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-decoration: none;
    width: 100%;
}
.btn-social:hover { background: #f5f5f0; color: #333; }
.form-control {
    border: 0.5px solid rgba(0,0,0,0.2);
    border-radius: 10px;
    height: 42px;
    font-size: 14px;
}
.form-control:focus {
    border-color: #534AB7;
    box-shadow: 0 0 0 3px #EEEDFE;
}
.divider {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #888;
    font-size: 12px;
}
.divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 0.5px;
    background: rgba(0,0,0,0.1);
}
</style>
</head>

<body>
    <div class="auth-card shadow-sm">

        {{-- Brand --}}
        <div class="text-center mb-4">
            <div class="brand-icon mb-2">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h5 class="fw-500 mb-1">AuthSecure</h5>
            <p class="text-muted mb-0" style="font-size:13px">Welcome</p>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="alert alert-danger py-2 px-3" style="font-size:13px">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Success --}}
        @if(session('success'))
            <div class="alert alert-success py-2 px-3" style="font-size:13px">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('login.success') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" style="font-size:13px;font-weight:500">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="name@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-2">
                <label class="form-label" style="font-size:13px;font-weight:500">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••">
                    <button type="button" class="btn btn-outline-secondary"
                            style="border-radius:0 10px 10px 0;border:0.5px solid rgba(0,0,0,0.2)"
                            onclick="togglePassword()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-end mb-3">
                <a href="{{ route('forgot.password') }}" style="font-size:12px;color:#534AB7">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="btn btn-purple w-100 mb-3">
                Login
            </button>
        </form>

        {{-- Divider --}}
        <div class="divider mb-3">or</div>

        {{-- Social Login --}}
        <a href="{{ route('auth.google') }}" class="btn-social mb-2">
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Login with Gmail
        </a>

        <a href="{{ route('auth.facebook') }}" class="btn-social">
            <svg width="18" height="18" viewBox="0 0 24 24">
                <path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Login with Facebook
        </a>

        <p class="text-center mt-3 mb-0" style="font-size:13px;color:#888">
            don't have an account?
            <a href="{{ route('registration') }}" style="color:#534AB7;font-weight:500">Registration</a>
        </p>

    </div>

    <script>
    function togglePassword() {
        const p = document.getElementById('password');
        p.type = p.type === 'password' ? 'text' : 'password';
    }
    </script>
</body>
</html>