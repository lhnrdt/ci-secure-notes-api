<?php

namespace App\Controllers;

use App\Models\NoteModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use phpDocumentor\Reflection\Types\This;
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
            $userId = validateAccessJWTFromRequest($encodedToken);

            $model = new NoteModel();
            $note = $model->findNoteById($id);
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