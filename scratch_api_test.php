<?php

$loginResponse = file_get_contents('https://jwt-auth-eight-neon.vercel.app/login', false, stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => json_encode([
            'email' => 'aprilyani.safitri@gmail.com',
            'password' => '123456'
        ])
    ]
]));

$loginData = json_decode($loginResponse, true);
$token = $loginData['refreshToken'] ?? $loginData['token'] ?? $loginData['accessToken'] ?? '';

echo "Token: " . substr($token, 0, 10) . "...\n";

if ($token) {
    $makulResponse = file_get_contents('https://jwt-auth-eight-neon.vercel.app/getMakul', false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => 'Authorization: Bearer ' . $token
        ]
    ]));
    
    echo "Makul Response:\n";
    print_r(json_decode($makulResponse, true));
}

