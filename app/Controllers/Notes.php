<?php

namespace App\Controllers;

use App\Models\NoteModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

/**
 *
 */
class Notes extends BaseController
{
    /**
     * get all notes matching the userID provided in the JWT
     * allows pagination and filtering by category and search query
     *
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {

        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userID = validateAccessJWTFromRequest($encodedToken);
            $model = new NoteModel();

            $limit = $_GET['limit'] ?? 0;
            $offset = $_GET['offset'] ?? 0;
            $categoryId = $_GET['category'] ?? null;
            $searchQuery = $_GET['q'] ?? null;

            $batch = $model->findNotesByUser($userID, $limit, $offset, $categoryId, $searchQuery);

            return $this->getResponse(
                [
                    'message' => 'User\'s notes retrieved successfully',
                    'note' => $batch['note'],
                    'hasMore' => $batch['hasMore']
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => $e->getMessage(),
                    'note' => [],
                    'hasMore' => false
                ]
            );
        }


    }


    /**
     *  Stores new note to database
     *
     */
    public function store(): ResponseInterface
    {
        $rules = [
            'title' => 'max_length[100]',
            'content' => 'max_length[1024]',
            'user_id' => 'required',
        ];

        $input = $this->getRequestInput($this->request);

        // if it doesn't validate respond with bad request
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
            $model = new NoteModel();
            $model->save($input);
            $note = $model->findNoteById($model->getInsertID());
            return $this
                ->getResponse(
                    [
                        'message' => 'Notes added successfully',
                        'note' => $note
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
     * Reads a specific note from the database
     *
     * @param $id
     * @return ResponseInterface
     */
    public function show($id): ResponseInterface
    {
        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userId = validateAccessJWTFromRequest($encodedToken);
            $model = new NoteModel();
            $note = $model->findNoteById($id);
            $model->checkOwnership($note, $userId);

            return $this
                ->getResponse(
                    [
                        'message' => 'Notes retrieved successfully',
                        'note' => $note
                    ]
                );

        } catch (Exception $e) {

            return $this
                ->getResponse(
                    [
                        'message' => $e->getMessage(),
                    ],
                    ResponseInterface::HTTP_NOT_FOUND
                );

        }
    }

    /**
     *
     * Deletes specified note from database
     *
     * @param $id
     * @return ResponseInterface
     */
    public function destroy($id): ResponseInterface
    {
        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userId = validateAccessJWTFromRequest($encodedToken);

            $model = new NoteModel();
            $note = $model->findNoteById($id);
            // check matching user ids
            $model->checkOwnership($note, $userId);
            $model->delete($id);

            return $this->getResponse(
                [
                    'message' => 'Note deleted',
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
     * Updates specified note
     *
     * @param $id
     * @return ResponseInterface
     */
    public function update($id): ResponseInterface
    {
        $rules = [
            'title' => 'max_length[100]',
            'content' => 'max_length[1024]',
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

            $model = new NoteModel();
            $note = $model->findNoteById($id);

            $model->checkOwnership($note, $userId);

            if (!isset($input['category_id'])) $input['category_id'] = null;

            $model->update($id, $input);

            return $this->getResponse(
                [
                    'message' => 'Note updated',
                    'note' => $model->findNoteById($id)
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