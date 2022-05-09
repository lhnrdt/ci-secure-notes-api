<?php

namespace App\Validation;

use App\Models\UserModel;
use Exception;

class UserRules
{
    /**
     * @throws Exception
     */
    public function validateUser(string $str, string $fields, array $data): bool
    {
        try {
            $model = new UserModel();
            $user = $model->findUserByEmailAddress($data['email']);
            return password_verify($data['email'].$data['password'], $user['password']);
        } catch (Exception $e) {
            return false;
        }
    }

}