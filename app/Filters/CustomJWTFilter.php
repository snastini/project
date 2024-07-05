<?php

namespace App\Filters;

use App\Exceptions\UnauthorizedException;
use App\Models\RoleModel;
use App\Models\UserModel;
use Codeigniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class CustomJWTFilter implements FilterInterface{
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = 'secret';
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');
        $splitHeader = explode(' ', $authHeader);
        
        if (count($splitHeader) != 2) {
            throw new UnauthorizedException('Invalid Token');
        }

        if ($splitHeader[0] != 'Bearer') {
            throw new UnauthorizedException('Invalid Token');
        }

        $jwt = $splitHeader[1];

        try {
            $jwtKey = new Key($key, 'HS256');
            $decoded = JWT::decode($jwt, $jwtKey);
            $decoded_array = (array)$decoded;
        } catch (ExpiredException $e) { 
            throw new UnauthorizedException('Token Expired');
        } catch (Exception $e) {
            throw  new UnauthorizedException('Invalid Token');
        }

/*         $now = time();

        if($now > $decoded_array['exp']){
            throw new UnauthorizedException('Token Expired');
        } */

        log_message('info','Decode: ' . json_encode($decoded_array));

        $user = new UserModel();
        $user = $user->where('email', $decoded_array['email'])->first();

        if(!$user){
            throw new UnauthorizedException('User Not Found');
        }

        $userPermissions = [];

        $cacheKey = 'user_permissions_'. $user['nip'];

        if(!cache($cacheKey)){
            $roleModel = new RoleModel();
            $userPermissions = $roleModel->builder()->select([
                                                            'roles.id role_id',
                                                            'roles.name as role_name',
                                                            'permissions.id as permission_id',
                                                            'permissions.name as permission_name' 
                                                        ])
                                                    ->join('user_roles','user_roles.role_id = role_id')
                                                    ->join('role_permissions', 'role_permissions.role_id =roles.id')
                                                    ->join('permissions', 'permissions.id = role_permissions.permission_id')
                                                    ->where('user_roles.user_id', $user['id'])
                                                    ->get()
                                                    ->getResultArray();

            $expiredCache = 60*60*24;

            cache()->save('user_permissions',$userPermissions, $expiredCache);
        } else {
            $userPermissions = cache('user_permissions');
        }                           
        
        $request->user = $user;
        $request->user_permissions = $userPermissions;

        return $request;

                  
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}