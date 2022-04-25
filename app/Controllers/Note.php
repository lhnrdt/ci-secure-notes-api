<?php

namespace App\Controllers;

use App\Models\NoteModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use ReflectionException;

class Note extends BaseController
{
    public function index(): ResponseInterface
    {
        $model = new NoteModel();
        return $this->getResponse(
            [
                'message' => 'Notes retrieved successfully',
                'notes' => $model->findAll()
            ]
        );
    }

    /**
     * @throws ReflectionException
     */
    public function store(): ResponseInterface
    {
        $rules = [];

        $input = $this->getRequestInput($this->request);

        // if it doesn't validate respond with bad request
        if (!$this->validateRequest($input, $rules)) {
            return $this
                ->getResponse(
                    $this->validator->getErrors(),
                    ResponseInterface::HTTP_BAD_REQUEST
                );
        }

        $model = new NoteModel();
        $model->save($input);

        return $this
            ->getResponse(
                [
                    'message' => 'Note added successfully',
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
                        'message' => 'Note retrieved successfully',
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

}