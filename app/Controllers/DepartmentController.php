<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class DepartmentController extends BaseController
{
    public function index(): ResponseInterface
    {

        $user = $this->request->user;
        $nip = $user['nip'];

        log_message('info','User'.json_encode($user));
        log_message('info','NIP'.$nip);

        $departmentModel = new DepartmentModel();
        $department = $departmentModel->findAll();
        
        return $this->response->setJSON([
            'message' => 'success',
            'data'  => $department
        ]);
    }

    public function store():ResponseInterface{
        $departmentModel = new DepartmentModel();
        $departmentModel->insert($this->request->getJSON());

        return $this->response->setJSON([
            'message'=> 'success']);
    }

    public function show(int $id): ResponseInterface
   {
        $departmentModel = new DepartmentModel();
        $department = $departmentModel->find($id);


       if(!$department){
        return $this->response->setStatusCode(404)
                              ->setJSON(['message' => 'Data Not Found',
                                         'data' => null]);
       }
       return $this->response->setJSON([
           'message' => 'success',
           'data' => $department
       ]);
   }


   public function update(int $id): ResponseInterface
   {
       $departmentModel = new DepartmentModel();
       $department = $departmentModel->find($id);

       if(!$department){
        return $this->response->setStatusCode(404)
                              ->setJSON(['message' => 'Data Not Found',
                                         'data' => null]);
       }

       $departmentModel->update($id, $this->request->getJSON());

       return $this->response->setJSON([
           'message' => 'success',
           'data' => null
       ]);
   }

   public function delete(int $id): ResponseInterface
   {
       $leaveTypeModel = new DepartmentModel();
       $leaveTypeModel->delete($id);

       return $this->response->setJSON([
           'message' => 'success',
           'data' => null
       ]);
   }
}
