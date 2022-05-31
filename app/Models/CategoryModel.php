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
    public function findCategoriesByUser($user) {
        $categories = $this->asArray()
            ->where(['user_id' => $user])
            ->findAll();

        if (!$categories) throw new Exception('No Categories found for this user.');

        return $categories;
    }

}