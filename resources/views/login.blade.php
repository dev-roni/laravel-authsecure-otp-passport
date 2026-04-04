<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>AuthSecure</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #667eea, #764ba2);
    height: 100vh;
}
.auth-card {
    backdrop-filter: blur(15px);
    background: rgba(255,255,255,0.1);
    border-radius: 20px;
    color: #fff;
}
.form-control {
    border-radius: 10px;
}
.btn-custom {
    border-radius: 10px;
    background: #fff;
    color: #333;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center h-100">
    <div class="col-md-4">
        <div class="auth-card p-4 shadow-lg">

            <h3 class="text-center mb-4">AuthSecure</h3>

            <form method="POST" action="/login">
                @csrf

                <input type="email" name="email" class="form-control mb-3" placeholder="Email">

                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                    <button type="button" class="btn btn-light" onclick="togglePassword()">👁</button>
                </div>

                <button class="btn btn-custom w-100">Login</button>
            </form>

            <p class="text-center mt-3">
                Don't have account? <a href="/register" class="text-white">Register</a>
            </p>

        </div>
    </div>
</div>

<script>
function togglePassword() {
    let pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>