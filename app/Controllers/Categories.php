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
                    'categories' => []
                ]
            );
        }
    }

    public function store(): ResponseInterface
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'color' => 'validate_color',
            'user_id' => 'required',
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    [
                        'message' => 'Validation failed'
                    ],
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        try {
            $model = new CategoryModel();
            $model->save($input);
            $category = $model->findCategoryById($model->getInsertID());
            return $this
                ->getResponse(
                    [
                        'message' => 'Category added successfully',
                        'category' => $category
                    ]
                );
        } catch (Exception $e) {
            return $this
                ->getResponse(
                    [
                        'message' => $e->getMessage()
                    ],
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
    }

    public function destroy($id): ResponseInterface
    {
        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userId = validateAccessJWTFromRequest($encodedToken);

            $model = new CategoryModel();
            $category = $model->findCategoryById($id);
            $model->checkOwnership($category, $userId);
            $model->delete($id);

            return $this
                ->getResponse(
                    [
                        'message' => 'Category deleted'
                    ]
                );
        } catch (Exception $e) {
            return $this
                ->getResponse(
                    [
                        'message' => $e->getMessage()
                    ],
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
    }

    public function  update($id): ResponseInterface
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'color' => 'validate_color',
            'user_id' => 'required',
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    [
                        'message' => 'Validation failed'
                    ],
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userId = validateAccessJWTFromRequest($encodedToken);

            $model = new CategoryModel();
            $category = $model->findCategoryById($id);
            $model->checkOwnership($category, $userId);
            $model->update($id, $input);

            return $this->getResponse(
                [
                    'message' => 'Category updated',
                    'category' => $model->findCategoryById($id)
                ]
            );
        } catch (Exception $e) {
            return $this
                ->getResponse(
                    [
                        'message' => $e->getMessage()
                    ],
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }
    }
}