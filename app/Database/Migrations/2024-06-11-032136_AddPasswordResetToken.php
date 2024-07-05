<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordResetToken extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'password_reset_token'=> [
                    'type'=> 'VARCHAR',
                    'constraint'=> 255,
                    'null'=> true,
                    'unique'=> true,
                    ],
            'password_reset_token_expiration'=> [
                    'type'=> 'TIMESTAMP',
                    'null'=> true,
                    ]
                ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users',[
            'password_reset_token','password_reset_token_expiriration'
        ]);
    }
}
