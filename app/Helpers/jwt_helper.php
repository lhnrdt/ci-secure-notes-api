<?php

use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) { //JWT is absent
        throw new Exception('Missing or invalid JWT in request');
    }
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return explode(' ', $authenticationHeader)[1];
}

/**
 * @throws Exception
 */
function validateAccessJWTFromRequest(string $encodedToken)
{
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));
    if ($decodedToken->type !== 'access') {
        throw new Exception('invalid token type');
    }
}

/**
 * @throws Exception
 */
function validateRefreshJWTFromRequest(string $encodedToken): string
{
    $key = Services::getSecretKey();
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'));

    if ($decodedToken->type !== 'refresh') {
        throw new Exception('invalid token type');
    }
    $model = new UserModel();
    $user = $model->find($decodedToken->userId);

    if ($user['password'] !== $decodedToken->key) {
        throw new Exception('password changed');
    }

    return getSignedAccessJWTForUser($user);
}

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


/**
 * @throws Exception
 */
function getUserID($authenticationHeader) {
    $encodedToken = getJWTFromRequest($authenticationHeader);
    $decodedToken = validateAccessJWTFromRequest($encodedToken);
    return $decodedToken['id'];
}