<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Categories extends BaseController
{
    public function index(): ResponseInterface
    {
        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userID = validateAccessJWTFromRequest($encodedToken);
            $model = new CategoryModel();

            return $this->getResponse(
                [
                    'message' => 'User\'s categories retrieved successfully.',
                    'categories' => $model->findCategoriesByUser($userID)
                ]
            );

        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => $e->getMessage(),
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
}