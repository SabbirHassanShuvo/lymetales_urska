<?php

function post($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['status' => $status, 'body' => json_decode($response, true)];
}

$baseUrl = "http://127.0.0.1:8001/api/auth";

echo "1. Registering User...\n";
$reg = post("$baseUrl/register", [
    "first_name" => "Test",
    "last_name" => "User",
    "email" => "testuser@example.com",
    "phone_number" => "1234567890",
    "password" => "password",
    "password_confirmation" => "password"
]);
print_r($reg);

echo "\n2. Attempting Login (Should fail with 403 because pending)...\n";
$login1 = post("$baseUrl/login", [
    "email" => "testuser@example.com",
    "password" => "password"
]);
print_r($login1);

echo "\n3. Testing Forgot Password...\n";
$forgot = post("$baseUrl/forgot-password", [
    "email" => "testuser@example.com"
]);
print_r($forgot);

if (isset($forgot['body']['otp'])) {
    $otp = $forgot['body']['otp'];
    echo "\n4. Verifying OTP ($otp)...\n";
    $verify = post("$baseUrl/verify-otp", [
        "email" => "testuser@example.com",
        "otp" => $otp
    ]);
    print_r($verify);

    echo "\n5. Resetting Password...\n";
    $reset = post("$baseUrl/reset-password", [
        "email" => "testuser@example.com",
        "otp" => $otp,
        "password" => "newpassword123",
        "password_confirmation" => "newpassword123"
    ]);
    print_r($reset);
}

echo "\nVerification script finished.\n";
