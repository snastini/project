<?php

namespace App\Exceptions;

use CodeIgniter\Exceptions\HTTPExceptionInterface;
use CodeIgniter\HTTP\Exceptions\HTTPException;

class ForbiddenAccessException extends HTTPException implements HTTPExceptionInterface{
    protected $code = 403;
    protected $message = "Anda tidak punya akses";
}

