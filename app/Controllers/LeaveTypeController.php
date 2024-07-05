<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LeaveTypeModel;
use CodeIgniter\HTTP\ResponseInterface;

class LeaveTypeController extends BaseController
{
    public function index(): ResponseInterface
    {
        $leaveTypeModel = new LeaveTypeModel();
        $leaveTypes = $leaveTypeModel->findAll();
        
        return $this->response->setJSON([
            'message' => 'success',
            'data'  => $leaveTypes
        ]);
    }

    public function store():ResponseInterface{
        $leaveTypeModel = new LeaveTypeModel();
        $leaveTypeModel->insert($this->request->getJSON());

        return $this->response->setJSON([
            'message'=> 'success']);
    }

    public function show(int $id): ResponseInterface
   {
       $leaveTypeModel = new LeaveTypeModel();
       $leaveType = $leaveTypeModel->find($id);


       if(!$leaveType){
        return $this->response->setStatusCode(404)
                              ->setJSON(['message' => 'Data Not Found',
                                         'data' => null]);
       }
       return $this->response->setJSON([
           'message' => 'success',
           'data' => $leaveType
       ]);
   }


   public function update(int $id): ResponseInterface
   {
       $leaveTypeModel = new LeaveTypeModel();
       $leaveType = $leaveTypeModel->find($id);

       if(!$leaveType){
        return $this->response->setStatusCode(404)
                              ->setJSON(['message' => 'Data Not Found',
                                         'data' => null]);
       }

       $leaveTypeModel->update($id, $this->request->getJSON());

       return $this->response->setJSON([
           'message' => 'success',
           'data' => null
       ]);
   }

   public function delete(int $id): ResponseInterface
   {
       $leaveTypeModel = new LeaveTypeModel();
       $leaveTypeModel->delete($id);

       return $this->response->setJSON([
           'message' => 'success',
           'data' => null
       ]);
   }

}

