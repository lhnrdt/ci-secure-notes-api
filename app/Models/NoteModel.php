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
            ->select('note.*, category.name category_name, category.color')
            ->join('category', 'category.id = note.category_id', 'left')
            ->where(['note.id' => $id])
            ->first();

        if (!$note) throw new Exception('Could not find note for specified ID');

        return $note;
    }

    /**
     * @throws Exception
     */
    public function findNotesByUser(
        $user,
        ?int $limit = 0,
        ?int $offset = 0,
        ?int $categoryID = null,
        ?string $searchQuery = null
    ): array
    {
        $builder = $this->builder();
        $builder
            ->select('note.*, category.name category_name, category.color')
            ->orderBy('id', 'DESC')
            ->join('category', 'category.id = note.category_id', 'left')
            ->where(['note.user_id' => $user]);

        // append where if query should be filtered by category
        if ($categoryID) {
            $builder->where('category.id', $categoryID);
        }

        // append filters if search query is given
        if ($searchQuery) {
            $builder->groupStart()
                ->orLike('category.name', $searchQuery)
                ->orLike('note.content', $searchQuery)
                ->orLike('note.title', $searchQuery)
                ->groupEnd();
        }

        // returns found notes and info if there are more notes available to prevent
        // unnecessary network calls when lazy loading content

        return [
            'note' => $builder->get($limit, $offset, false)->getResult(),
            'hasMore' => count($builder->get($limit, $offset + $limit)->getResult()) > 0,
        ];
    }

    /**
     *
     * @throws Exception
     */
    public function checkOwnership($note, $userId)
    {
        if ($note['user_id'] != $userId) throw new Exception('This note does not belong to the user.');
    }
}