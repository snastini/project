<?php

namespace App\Exceptions;

use CodeIgniter\Exceptions\HTTPExceptionInterface;
use CodeIgniter\HTTP\Exceptions\HTTPException;

class UnauthorizedException extends HTTPException implements HTTPExceptionInterface{
    protected $code = 401;
    protected $message = "Anda belum login";
}

