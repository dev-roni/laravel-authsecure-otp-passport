<?php

namespace App\Services;

class SmsService
{
    public function send($phone, $otp)
    {
        $url = "http://api.greenweb.com.bd/api.php";

        $data = [
            'token'   => '119011859261764853166111c7be6ff21458b3c6fc41fd02c9773', // <- আপনার টোকেন বসাবেন
            'to'      => $phone,
            'message' => "Your OTP is: $otp",
        ];

        // ---- cURL START ----
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $err      = curl_error($ch);

        curl_close($ch);
        // ---- cURL END ----

        if ($err) {
            return ['status' => false, 'error' => $err];
        }

        return ['status' => true, 'response' => $response];
    }
}
