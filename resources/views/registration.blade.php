<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AuthSecure - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            height: 100vh;
        }
        .card {
            border-radius: 15px;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center h-100">
    <div class="col-md-5">
        <div class="card shadow-lg p-4">
            
            <h3 class="text-center mb-4">🔐 AuthSecure Register</h3>

            <form method="POST" action="{{route('register')}}">
                @csrf

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" value="{{old('name')}}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{old('email')}}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{old('phone')}}" class="form-control @error('phone') is-invalid @enderror" placeholder="01XXXXXXXXX">
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Registration
                </button>
            </form>

            <p class="text-center mt-3">
                Already have account? <a href="/login">Login</a>
            </p>

        </div>
    </div>
</div>

</body>
</html>