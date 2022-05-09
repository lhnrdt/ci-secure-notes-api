<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;
use Faker\Factory;

class NoteSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->db->table('note')->insert($this->generateNote());
        }
    }

    /**
     * @throws Exception
     */
    private function generateNote(): array
    {
        $faker = Factory::create();
        return [
            'title' => $faker->sentence(3, true),
            'content' => $faker->sentence(25, true),
            'user_id' => 1,
            'category_id' => random_int(0, 9)
        ];
    }
}
