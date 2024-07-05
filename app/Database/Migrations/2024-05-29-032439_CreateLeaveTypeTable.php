<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLeaveTypeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id"=> ["type"=> "INT",
                    "unsigned"=> true,
                    "auto_increment"=> true],
            "name"=> ["type"=> "VARCHAR",
                      "constraint"=> 100],
        ]);

        $this->forge->addKey("id", true);
        $this->forge->createTable("leave_types", true);
    }

    public function down()
    {
        $this->forge->dropTable('leave_types',true);
    }
}
