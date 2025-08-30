<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createJWT($user)
{
    $issuedAt   = time();
    $expire     = $issuedAt + env('JWT_TIME_TO_LIVE'); // 1 jam
    $payload = [
        'iss' => base_url(),
        'iat' => $issuedAt,
        'exp' => $expire,
        'uid' => $user['id'],
        'email' => $user['email'],
    ];

    return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
}

function validateJWT($token)
{
    try {
        return JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
    } catch (\Exception $e) {
        return null; // token invalid/expired
    }
}