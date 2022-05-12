<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class NoteModel extends Model
{
    protected $table = 'note';
    protected $primaryKey = 'id';


    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['title', 'content', 'user_id', 'category_id'];

    protected $useTimestamps = true;
    protected $updatedField = 'updated_at';
    protected $createdField = 'created_at';


    /**
     * @throws Exception
     */
    public function findNoteById($id)
    {
        $note = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$note) throw new Exception('Could not find note for specified ID');

        return $note;
    }

    /**
     * @throws Exception
     */
    public function findNotesByUser($user): array
    {
        $note = $this
            ->asArray()
            ->orderBy('created_at', 'ASC')
            ->where(['user_id' => $user])
            ->findAll();

        if (!$note) throw new Exception('Could not find note for specified ID');

        return $note;
    }
}