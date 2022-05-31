<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'color' =>  [
                'type' => 'VARCHAR',
                'constraint' => '7',
                'null' => false,
                'default' => '#ffffff'
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('category');
    }

    public function down()
    {
        //
    }
}
