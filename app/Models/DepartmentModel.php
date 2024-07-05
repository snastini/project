<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table            = 'departments';
    protected $primaryKey = "id";
    protected $useAutoIncrement = true;
    protected $allowedFields = ['name', 'parent_id'];
    protected $useTimestamps = true;
}
