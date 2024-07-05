<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{

    public function __construct()
    {
        helper('cookie');
    }
    public function login()
    {
        $payload = $this->request->getJSON();

        $user = new UserModel();
        $userData = $user->where("nip", $payload->nip)->first();

        if(!$userData) {
            return $this->response->setJSON([
                "message"=> "failed",
                "data" => "User Not Found"               
            ])->setStatusCode(401);
        }

        $verifyPassword = password_verify($payload->password, $userData['password']);

        if(!$verifyPassword){
            return $this->response->setJSON([
                'message' => 'failed',
                'data'=> 'Invalid Password'])->setStatusCode(401);
        }

        $key = 'secret';
        $now = new DateTime();
        $expirationTime = new DateTime();
        $exp = $expirationTime->modify('+1 day')->getTimestamp();

        $jwtPayload = [
            'iss' => 'https://api.bmkg.go.id',
            'aud' => 'https://api.bmkg.go.id',
            'iat'=>  $now->getTimestamp(),
            'exp' => $exp,
            'nip' => $userData['nip'],
            'email' => $userData['email'],
        ];

        $oneday = 60*60*24;

        $token = JWT::encode($jwtPayload, $key, 'HS256');

        set_cookie(
            name: 'access_token',
            value: $token,
            expire: $oneday,
            httpOnly:true
        );

        return $this->response->setJSON([
            'message' => 'success',
            'data' => ['access_token' => $token]
        ]);
    }

    public function register(){
        $payload = $this->request->getJSON();

        $rules = [
            'name' => 'required',
            'nip' => 'required|is_unique[users.nip]',
            'email'=> 'required|valid_email',
            'password'=> 'required',
        ];

        $payloadArray = (array)$payload;

        if(!$this->validateData($payloadArray, $rules)){
            return $this->response->setJSON([
                'message' => 'failed',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(400);
        }

        $password = password_hash($payload->password, PASSWORD_BCRYPT);

        $user = new UserModel();

        $user->insert([
            'name' => $payload->name,
            'email' => $payload->email,
            'password'=> $password,
            'nip' => $payload->nip
        ]);
    }

    public function getProfile(){
        $currentUser = $this->request->user;

        $user = new UserModel();

        $userData = $user->where('nip', $currentUser['nip'])
                        ->first();

    
        $roleModel = new RoleModel();
        $userPermissions = $roleModel->builder()->select([
                                                'roles.id role_id',
                                                'roles.name as role_name',
                                                'permissions.id as permission_id',
                                                'permissions.name as permission_name' 
                                            ])
                                        ->join('user_roles','user_roles.role_id = roles.id')
                                        ->join('role_permissions', 'role_permissions.role_id = roles.id')
                                        ->join('permissions', 'permissions.id = role_permissions.permission_id')
                                        ->where('user_roles.user_id', $userData['id'])
                                        ->get()
                                        ->getResultArray();


        $groupByRole = [];

        foreach($userPermissions as $permission){
            $roleName = $permission['role_name'];
            $permissionName = $permission['permission_name'];

            if(!isset($groupByRole[$roleName])){
                $groupByRole[$roleName] = [];
            }

            $groupByRole[$roleName][] = $permissionName;
        }


        return $this->response->setJSON([
            'message'=> 'success',
            'data'=> [
                'user'=>$userData,
                'permissions' => $userPermissions,
                'roles'=> $groupByRole]
            ]
        );
    }
}
