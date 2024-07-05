<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
           ],
           'name' => [
               'type' => 'VARCHAR',
               'constraint' => 100,
           ],
           'created_at' => [
               'type' => 'TIMESTAMP',
               'null' => true,
           ],
           'updated_at' => [
               'type' => 'TIMESTAMP',
               'null' => true,
           ],
       ]);

       $this->forge->addPrimaryKey('id');
       $this->forge->createTable('positions', true);

       $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'parent_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);


        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('parent_id', 'departments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('departments', true);

       // employees table
       $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'nip' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'position_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'department_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'join_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('position_id', 'positions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('department_id', 'departments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('employees', true);
 
    }

    public function down()
    {
        $this->forge->dropTable('employees', true);
        $this->forge->dropTable('departments', true);
        $this->forge->dropTable('positions', true);
    }
}
