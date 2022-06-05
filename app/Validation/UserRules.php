<?php

namespace App\Validation;

use App\Models\UserModel;
use Exception;

class UserRules
{
    /**
     * @throws Exception
     */
    public function validate_user(string $str, string $fields, array $data): bool
    {
        try {
            $model = new UserModel();
            $user = $model->findUserByEmailAddress($data['email']);
            return password_verify($data['password'], $user['password']);
        } catch (Exception $e) {
            return false;
        }
    }

    public function validate_color(string $str): bool
    {
        return preg_match('/^#([A-Fa-f\d]{6}|[A-Fa-f\d]{3})$/', $str);
    }

}