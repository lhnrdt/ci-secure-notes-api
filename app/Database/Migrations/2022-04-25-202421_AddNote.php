<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNote extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'content' => [
                'type' => 'VARCHAR',
                'constraint' => '1024',
                'null' => false
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('category_id', 'category', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('note');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('note');
    }
}
