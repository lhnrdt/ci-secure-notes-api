<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

class Auth extends BaseController
{
    /**
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
        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $userModel = new UserModel();
        $userModel->save($input);


        return $this
            ->getJWTForUser(
                $input['email'],
                ResponseInterface::HTTP_CREATED
            );

    }

    /**
     * Authenticate Existing User
     * @return ResponseInterface
     */
    public function login(): ResponseInterface
    {
        $rules = [
            'email' => 'required|min_length[6]|max_length[50]|valid_email',
            'password' => 'required|min_length[8]|max_length[255]|validateUser[email, password]'
        ];

        $errors = [
            'password' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);


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
     * @throws Exception
     */
    public function refreshToken(): ResponseInterface
    {
        try {
            helper('jwt');
            helper('cookie');
            $encodedToken = get_cookie('refresh_token');
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
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
    }

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

            set_cookie('refresh_token',
                getSignedRefreshJWTForUser($user),
                '',
                '',
                '/',
                '',
                false,
                true
            );

            unset($user["password"]);

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
}