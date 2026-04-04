<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OTP Verification</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    height: 100vh;
}

.card {
    border-radius: 20px;
}

.otp-box {
    width: 50px;
    height: 55px;
    font-size: 22px;
    text-align: center;
    border-radius: 10px;
}

.verify-btn {
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-4">
        <div class="card shadow-lg p-4 text-center">

            <h4 class="mb-2">🔐 OTP Verification</h4>
            <p class="text-muted">Enter the 6-digit code</p>

            <form method="POST" action="/verify-otp">
                <input type="hidden" name="otp" id="otp">

                <div class="d-flex justify-content-between mb-3">
                    <input type="text" maxlength="1" class="form-control otp-box">
                    <input type="text" maxlength="1" class="form-control otp-box">
                    <input type="text" maxlength="1" class="form-control otp-box">
                    <input type="text" maxlength="1" class="form-control otp-box">
                    <input type="text" maxlength="1" class="form-control otp-box">
                    <input type="text" maxlength="1" class="form-control otp-box">
                </div>

                <button class="btn btn-primary w-100 verify-btn">
                    Verify OTP
                </button>
            </form>

            <p class="mt-3">
                <span id="timer-text">Resend OTP in <span id="timer">30</span>s</span>
            </p>

            <a href="{{route('resend.otp')}}" id="resendBtn" class="btn btn-outline-primary w-100" disabled>
                Resend OTP
            </a>

        </div>
    </div>
</div>

<script>
const inputs = document.querySelectorAll('.otp-box');

// Auto move + combine
inputs.forEach((input, i) => {

    input.addEventListener('input', () => {
        if (input.value && inputs[i + 1]) {
            inputs[i + 1].focus();
        }
        updateOTP();
    });

    // 🔥 Backspace 
    input.addEventListener('keydown', (e) => {
        if (e.key === "Backspace") {
            if (input.value === "" && inputs[i - 1]) {
                inputs[i - 1].focus();
            } else {
                input.value = "";
            }
        }
    });

    // Only numbers
    input.addEventListener('keypress', (e) => {
        if (!/[0-9]/.test(e.key)) e.preventDefault();
    });
});

// Combine OTP
function updateOTP() {
    let otp = '';
    inputs.forEach(input => otp += input.value);
    document.getElementById('otp').value = otp;
}

// Paste support
inputs[0].addEventListener('paste', (e) => {
    let data = e.clipboardData.getData('text').slice(0, 6);
    inputs.forEach((input, i) => {
        input.value = data[i] || '';
    });
    updateOTP();
});

// Timer + resend
let time = 30;
let timer = setInterval(() => {
    time--;
    document.getElementById('timer').innerText = time;

    if (time <= 0) {
        clearInterval(timer);
        document.getElementById('timer-text').innerText = "You can resend OTP now";
        document.getElementById('resendBtn').disabled = false;
    }
}, 1000);

// Resend click
document.getElementById('resendBtn').addEventListener('click', function () {
    this.disabled = true;

    alert("OTP resent!");

    let time = 60;
    document.getElementById('timer-text').innerHTML = 'Resend OTP in <span id="timer">30</span>s';

    let timer = setInterval(() => {
        time--;
        document.getElementById('timer').innerText = time;

        if (time <= 0) {
            clearInterval(timer);
            document.getElementById('timer-text').innerText = "You can resend OTP now";
            document.getElementById('resendBtn').disabled = false;
        }
    }, 1000);
});
</script>

</body>
</html>