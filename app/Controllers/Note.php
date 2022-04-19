<?php

namespace App\Controllers;

use App\Models\NoteModel;
use CodeIgniter\HTTP\ResponseInterface;

class Note extends BaseController
{
    public function index(): ResponseInterface
    {
        $model = new NoteModel();
        return $this->getResponse(
            [
                'message' => 'Notes retrieved successfully',
                'notes' => $model-> findAll()
            ]
        );
    }
}