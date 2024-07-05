<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveTypeModel extends Model{
    protected $table = "leave_types";
    protected $primaryKey = "id";
    protected $useAutoIncrement = true;
    protected $allowedFields = ['name'];
    protected $useTimestamps = false;
}