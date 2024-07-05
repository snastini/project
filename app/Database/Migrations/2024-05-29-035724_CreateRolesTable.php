<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesTable extends Migration
{
    public function up()
    {
        // role table
        $this->forge->addField([
            "id"=> ["type"=> "INT",
                "unsigned"=> true,
                "auto_increment"=> true],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ] 
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('roles', true);

        //user_roles Table
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
            'role_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
        ]);

        $this->forge->addPrimaryKey(['id']);
        $this->forge->addUniqueKey(['user_id', 'role_id'], 'user_roles_user_id_role_id_unique');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_roles', true);

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
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('permissions', true);


       // role_permissions Table


       $this->forge->addField([
           'id' => [
               'type' => 'INT',
               'unsigned' => true,
               'auto_increment' => true,
           ],
           'role_id' => [
               'type' => 'INT',
               'unsigned' => true,
           ],
           'permission_id' => [
               'type' => 'INT',
               'unsigned' => true,
           ],
       ]);

       $this->forge->addPrimaryKey('id');
       $this->forge->addUniqueKey(['role_id', 'permission_id'], 'role_permissions_role_id_permission_id_unique');
       $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
       $this->forge->addForeignKey('permission_id', 'permissions', 'id', 'CASCADE', 'CASCADE');
       $this->forge->createTable('role_permissions', true);


    }

    public function down()
    {
        $this->forge->dropTable('role_permission', true);
        $this->forge->dropTable('permissions', true);
        $this->forge->dropTable('user_roles', true);
        $this->forge->dropTable('roles', true);
    }
}
