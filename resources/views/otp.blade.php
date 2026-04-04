<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="col-md-4">
        <div class="card p-4 shadow-lg">

            <h4 class="text-center mb-3">Enter OTP</h4>

            <form method="POST" action="/verify-otp">
                @csrf

                <input type="text" name="otp" class="form-control text-center mb-3" placeholder="Enter OTP">

                <button type="submit" class="btn btn-success w-100">Verify</button>
            </form>

            <div class="text-center mt-3">
                <a href="#">Resend OTP</a>
            </div>

        </div>
    </div>
</div>