<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class CategoryModel extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';


    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = ['name', 'color', 'user_id'];

    /**
     * @throws Exception
     */
    public function findCategoriesByUser($user): array
    {
        $categories = $this->asArray()
            ->where(['user_id' => $user])
            ->findAll();

        if (!$categories) throw new Exception('No Categories found for this user.');

        return $categories;
    }

    /**
     * @throws Exception
     */
    public function findCategoryById($id)
    {
        $category = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$category) throw new Exception('Could not find Category with specified ID '.$id);

        return $category;
    }

    /**
     * @throws Exception
     */
    public function checkOwnership($category, $userId)
    {
        if ($category['user_id'] != $userId) throw new Exception('This category does not belong to the user.');
    }

}