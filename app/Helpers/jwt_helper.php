<?php

use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Returns the bearer token from http request if it exists
 *
 * @param $authenticationHeader
 * @return string
 * @throws Exception
 */
function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) {
        throw new Exception('Missing or invalid JWT in request');
    }
    return explode(' ', $authenticationHeader)[1];
}

/**
 * Validates an encoded access token and returns its user id
 *
 * @throws Exception
 */
function validateAccessJWTFromRequest(string $encodedToken): string
{
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    if ($decodedToken->type !== 'access') {
        throw new Exception('invalid token type');
    }
    return $decodedToken->userId;
}

/**
 * Validates an encoded refresh token and returns its user id
 *
 * @throws Exception
 */
function validateRefreshJWTFromRequest(string $encodedToken): string
{
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));

    if ($decodedToken->type !== 'refresh') {
        throw new Exception('invalid token type');
    }

    // check if password hashes still match
    $model = new UserModel();
    $user = $model->find($decodedToken->userId);

    if ($user['password'] !== $decodedToken->key) {
        throw new Exception('password changed');
    }

    return getSignedAccessJWTForUser($user);
}

/**
 * Issues new access JWT for a given user
 *
 * @param array $user
 * @return string
 */
function getSignedAccessJWTForUser(array $user): string
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_ACCESS_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'type' => 'access',
        'userId' => $user['id'],
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];

    return JWT::encode($payload, Services::getSecretKey(), 'HS256');
}

/**
 * Issues new refresh JWT for a given user
 *
 * @param array $user
 * @return string
 */
function getSignedRefreshJWTForUser(array $user): string
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_REFRESH_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;

    $payload = [
        'type' => 'refresh',
        'userId' => $user['id'],
        'key' => $user['password'],
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];

    return JWT::encode($payload, Services::getSecretKey(), 'HS256');
}
