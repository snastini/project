<?php
namespace App\Models;
use CodeIgniter\Model;

class LeaveModel extends Model{
    protected $table = "leaves";
    protected $primaryKey = "id";
    protected $useAutoIncrement = true;
    protected $allowedFields = ['user_id', 'start_date','end_date'];
    protected $useTimestamps = false;
}