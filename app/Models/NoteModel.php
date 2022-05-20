<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use phpDocumentor\Reflection\Types\Null_;

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
    public function findNotesByUser($user, ?int $limit = 0, ?int $offset = 0): array
    {
        $this->asArray()
            ->orderBy('id', 'ASC')
            ->where(['user_id' => $user]);

        $note = $this->findAll($limit, $offset);

        if (!$note) throw new Exception('Could not find note for specified ID');

        return $note;
    }

    /**
     * @throws Exception
     */
    public function deleteUserNote($userId, $noteId)
    {
        $note = $this->findNoteById($noteId);
        if ($note['user_id'] != $userId) throw new Exception('This note does not belong to the user.');
        else $this->delete($noteId);
    }
}