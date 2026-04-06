<?php

namespace App\Services;

class SmsService
{
    public function send($phone, $otp)
    {
        $url   = env('SMS_URL'); //<-add your sms api in env

        $data = [
            'token'   => env('SMS_TOKEN'), //  <-sms server access token add in env
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
