<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

/**
 *
 */
class Categories extends BaseController
{
    /**
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        try {

            // get userid from provided jwt token
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


    /**
     * Allows storing a new category to the database
     *
     * @return ResponseInterface containing the created category
     */
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

    /**
     * Deletes a specified category from the database
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function destroy(int $id): ResponseInterface
    {
        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userId = validateAccessJWTFromRequest($encodedToken);

            $model = new CategoryModel();
            $category = $model->findCategoryById($id);
            // test if the category's userid is matching the one provided in the jwt
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

    /**
     *
     * Updates a category with given id
     *
     * @param int $id category id
     * @return ResponseInterface
     */
    public function update(int $id): ResponseInterface
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