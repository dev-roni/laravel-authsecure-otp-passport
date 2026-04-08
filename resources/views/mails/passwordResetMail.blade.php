
{{-- resources/views/emails/password-reset.blade.php --}}
<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; padding: 20px;">

    <h2>Password Reset</h2>

    {{--  Web এর জন্য — link সহ button --}}

    @if(!request()->is('api/*'))
        <p>আপনার Password reset করতে নিচের button এ click করুন:</p>

        {{--  Web এর জন্য — link সহ button --}}
        <a href="{{ $url }}"
        style="
            background: #4F46E5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin: 16px 0;
        ">
            Reset Password
        </a>
    @endif

    {{--  API এর জন্য — শুধু token --}}

    @if(request()->is('api/*'))
    <p>আপনার token হচ্ছে :</p>
    <h3 style="background: #F3F4F6; padding: 12px; border-radius: 8px;">
        {{ $token }}
    </h3>
    @endif

    <p>এই link ও token ৬০ মিনিট valid থাকবে।</p>

    <p style="color: #6B7280; font-size: 12px;">
        যদি আপনি Password reset request না করে থাকেন তাহলে এই email ignore করুন।
    </p>

</body>
</html>