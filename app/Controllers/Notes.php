<?php

namespace App\Controllers;

use App\Models\NoteModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

class Notes extends BaseController
{
    public function index(): ResponseInterface
    {

        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userID = validateAccessJWTFromRequest($encodedToken);
            $model = new NoteModel();

            $limit = $_GET['limit'] ?? 0;
            $offset = $_GET['offset'] ?? 0;

            return $this->getResponse(
                [
                    'message' => 'User\'s notes retrieved successfully',
                    'note' => $model->findNotesByUser($userID, $limit, $offset)
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => $e->getMessage(),
                    'note' => []
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }


    }


    /**
     * @throws ReflectionException
     */
    public function store(): ResponseInterface
    {
        $rules = [
            'title' => 'max_length[100]',
            'content' => 'max_length[1024]',
            'user_id' => 'required',
            'category_id' => 'required',
        ];

        $input = $this->getRequestInput($this->request);

        // if it doesn't validate respond with bad request
        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $input,
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $model = new NoteModel();
        $model->save($input);

        return $this
            ->getResponse(
                [
                    'message' => 'Notes added successfully',
                    'notes' => $input
                ]
            );

    }

    public function show($id): ResponseInterface
    {
        try {
            $model = new NoteModel();
            $note = $model->findNoteById($id);

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
                        'message' => 'Could not find not for specified ID',
                    ],
                    ResponseInterface::HTTP_NOT_FOUND
                );

        }
    }

    public function destroy($id): ResponseInterface
    {
        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($this->request->getServer('HTTP_AUTHORIZATION'));
            $userID = validateAccessJWTFromRequest($encodedToken);

            $model = new NoteModel();
            $model->deleteUserNote($userID, $id);

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


}