<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('users')->insert($this->createUser());
    }

    private  function createUser() {
        return [
            'username' => 'Admin User',
            'email' => 'admin@admin.de',
            'password' => password_hash('admin12345', PASSWORD_BCRYPT),
        ];
    }
}
