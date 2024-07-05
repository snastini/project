<?php

namespace App\Filters;
use App\Exceptions\ForbiddenAccessException;    
use App\Exception\UnauthorizedException;
use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userPermissions = $request->user_permissions;
        $mapUserPermissions = array_map(function ($permission){
            return $permission['permission_name'];
        }, $userPermissions);


        foreach ($arguments as $argumen){  
            if(!in_array($argumen, $mapUserPermissions)){
                throw new ForbiddenAccessException("You do not have permission to access this resource");
            }

        }

        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}