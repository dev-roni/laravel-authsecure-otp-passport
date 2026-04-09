<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    height: 100vh;
}

.card {
    border-radius: 15px;
}

.btn-custom {
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-4">
        <div class="card shadow-lg p-4">

            <h4 class="text-center mb-3">Forgot Password</h4>
            <p class="text-muted text-center">Enter your email to receive reset link</p>

            <form method="POST" action="{{route('token.sent')}}">
                @csrf

                <div class="mb-3">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-custom">
                    Send Reset Link
                </button>
            </form>

            <p class="text-center mt-3">
                <a href="/login">Back to Login</a>
            </p>

        </div>
    </div>
</div>

</body>
</html>