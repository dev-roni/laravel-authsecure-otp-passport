<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Success</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #00c9ff, #92fe9d);
    height: 100vh;
}

.card {
    border-radius: 20px;
    text-align: center;
}

.icon {
    font-size: 60px;
    color: #28a745;
}
.profile-picture-container {
    position: relative;
    width: 150px;
    height: 150px;
}
.profile-picture {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 6px solid var(--primary-light);
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-4">
        <div class="card shadow-lg p-5">

            <div class="icon mb-3">✔</div>

            <h4 class="mb-2">Success!</h4>

            @if(!request()->routeIs('token.sent'))
                <p class="text-muted">
                Login successfully
                </p>

                <div class="profile-picture-container mx-auto">
                    <img src="{{ auth()->user()->avatar ?? ''}}" alt="Profile Image" class="profile-picture" >
                </div>

                <div class="row mb-2">
                    <div class="col-4 fw-bold">Name:</div>
                    <div class="col-8">{{ auth()->user()->name }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-4 fw-bold">Email:</div>
                    <div class="col-8">{{ auth()->user()->email }}</div>
                </div>

                <div class="row mb-2">
                    <div class="col-4 fw-bold">Phone:</div>
                    <div class="col-8">{{ auth()->user()->phone ?? 'N/A' }}</div>
                </div>
            @endif
            @if(request()->routeIs('token.sent'))
            <p class="text-muted">
                Your email has been sent successfully.
            </p>
            <p class="text-muted">
                We have sent a password reset link to <strong>{{$email}}</strong>.
                Please check your inbox.
            </p>
            @endif

            <a href="{{route('login')}}" class="btn btn-success mt-3">
                Back to Login
            </a>

        </div>
    </div>
</div>

</body>
</html>