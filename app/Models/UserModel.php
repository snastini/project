<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $useAutoIncrement = true;
    protected $allowedFields = ['name', 'nip', 'email', 'password' , 'password_reset_token', 'password_reset_token_expiration'];
    protected $useTimestamps = false;
}