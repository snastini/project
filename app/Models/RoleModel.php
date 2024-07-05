<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = "roles";
    protected $primaryKey = "id";
    protected $useAutoIncrement = true;
    protected $allowedFields = ['name'];
    protected $useTimestamps = false;
}