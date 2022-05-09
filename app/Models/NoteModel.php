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

    protected $allowedFields = ['title', 'content'];

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
            ->join('category', 'note.category_id = category.id')
            ->where(['id' => $id])
            ->first();

        if (!$note) throw new Exception('Could not find note for specified ID');

        return $note;
    }

    public function findNotesByUser($user): array
    {
        return $this
            ->asArray()
            ->join('category', 'note.category_id = category.id')
            ->where(['user_id' => $user])
            ->findAll();
    }
}