<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $colors = ["#fbb4ae","#b3cde3","#ccebc5","#decbe4","#fed9a6","#ffffcc","#e5d8bd","#fddaec","#f2f2f2"];
        for ($i = 0; $i < 9; $i++) {
            $this->db->table('category')->insert($this->generateCategory(array_pop($colors)));
        }
    }

    public function generateCategory($color) :array
    {
        $faker = Factory::create('en_US');

        return [
            'name' => $faker->word(),
            'color' => $color,
            'user_id' => 1
        ];
    }
}
