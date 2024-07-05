<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLeaveTypeIdOnLeavesTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('leaves', [
            'leave_type_id'=>['type'=>'INT','unsigned'=> true],
        ]);

        $this->forge->addForeignKey('leave_type_id', 'leave_types', 'id', 'CASCADE', 'CASCADE', 'leaves_leave_type_id_foreign');
        $this->forge->processIndexes('leaves');
    }

    public function down()
    {
        //$this->forge->dropForeignKey('leaves', 'leaves_leave_type_id_foreign');
        $this->forge->dropColumn('leaves','leave_type_id');
    }
}
