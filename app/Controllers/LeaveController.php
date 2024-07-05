<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LeaveModel;
use CodeIgniter\HTTP\ResponseInterface;

class LeaveController extends BaseController
{
    public function index(): ResponseInterface
    {
        $leaveModel = new LeaveModel();
        $leaves = $leaveModel->join('leave_types', 'leave_types.id = leaves.leave_type_id')
                            ->select('leaves.*, leave_types.name as leave_type_name')
                            ->findAll();
        
        return $this->response->setJSON([
            'message' => 'success',
            'data'  => $leaves
        ]);
    }

    public function store():ResponseInterface{
        $leaveModel = new LeaveModel();
        $leaveModel->insert($this->request->getJSON());

        return $this->response->setJSON([
            'message'=> 'success']);
    }

    public function show(int $id): ResponseInterface
   {
       $leaveModel = new LeaveModel();
       $leave = $leaveModel->find($id);


       if(!$leave){
        return $this->response->setStatusCode(404)
                              ->setJSON(['message' => 'Data Not Found',
                                         'data' => null]);
       }
       return $this->response->setJSON([
           'message' => 'success',
           'data' => $leave
       ]);
   }


   public function update(int $id): ResponseInterface
   {
       $leaveModel = new LeaveModel();
       $leave = $leaveModel->find($id);

       if(!$leave){
        return $this->response->setStatusCode(404)
                              ->setJSON(['message' => 'Data Not Found',
                                         'data' => null]);
       }

       $leaveModel->update($id, $this->request->getJSON());

       return $this->response->setJSON([
           'message' => 'success',
           'data' => null
       ]);
   }

   public function delete(int $id): ResponseInterface
   {
       $leaveModel = new LeaveModel();
       $leaveModel->delete($id);

       return $this->response->setJSON([
           'message' => 'success',
           'data' => null
       ]);
   }

}

