<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

/**
 * Authentication Controller
 */
class Auth extends BaseController
{
    /**
     * Allows the user to register himself
     *
     * @throws ReflectionException
     */
    public function register(): ResponseInterface
    {
        $rules = [
            'username' => 'required',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[255]'
        ];

        $input = $this->getRequestInput($this->request);

        // validate user input
        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $userModel = new UserModel();
        $userModel->save($input);

        // create auth tokens for new user
        return $this
            ->getJWTForUser(
                $input['email'],
                ResponseInterface::HTTP_CREATED
            );
    }

    /**
     * @param string $emailAddress
     * @param int $responseCode
     * @return ResponseInterface
     */
    private function getJWTForUser(
        string $emailAddress,
        int    $responseCode = ResponseInterface::HTTP_OK
    ): ResponseInterface
    {
        try {
            $model = new UserModel();
            $user = $model->findUserByEmailAddress($emailAddress);

            helper('jwt');
            helper('cookie');

            // save the refresh token as http-only cookie to prevent Clientside access
            set_cookie('refresh_token',
                getSignedRefreshJWTForUser($user),
                '', '', '/', '', false, true
            );

            // remove the password from the returned user
            unset($user["password"]);

            // respond with user and access token
            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'access_token' => getSignedAccessJWTForUser($user),
                        'user' => $user
                    ],
                    $responseCode
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }

    /**
     * Authenticate Existing User
     * @return ResponseInterface
     */
    public function login(): ResponseInterface
    {
        $rules = [
            'email' => 'required|min_length[6]|max_length[50]|valid_email',
            'password' => 'required|min_length[8]|max_length[255]|validate_user[email, password]'
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);

        // validate credentials
        if (!$this->validateRequest($input, $rules, $errors)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
        return $this->getJWTForUser($input['email']);
    }

    /**
     * Delete refresh token cookie
     *
     * @return ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        helper('cookie');
        delete_cookie('refresh_token');
        return $this
            ->getResponse(
                [
                    'message' => 'Refresh token cookie deleted.'
                ]
            );
    }

    /**
     * validates refresh token and issues new access token
     *
     * @throws Exception
     */
    public function refreshToken(): ResponseInterface
    {
        try {
            helper('jwt');
            helper('cookie');
            $encodedToken = get_cookie('refresh_token');
            if (!$encodedToken) throw new Exception('Missing or invalid refresh JWT in request');
            $newAccessToken = validateRefreshJWTFromRequest($encodedToken);
            return $this
                ->getResponse(
                    [
                        'message' => 'New Access Token Issued',
                        'access_token' => $newAccessToken,
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    ResponseInterface::HTTP_UNAUTHORIZED
                );
        }
    }
}