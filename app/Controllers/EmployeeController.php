<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use CodeIgniter\HTTP\ResponseInterface;

class EmployeeController extends BaseController
{
    public function index(): ResponseInterface
    {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->findAll();

        return $this->response->setJSON([
            "message" => "success",
            "data"=> $employee
           
        ]);
    }

    public function store(): ResponseInterface{
        $employeeModel = new EmployeeModel();
        $employeeModel->insert($this->request->getJSON());
        
        return $this->response->setJSON([
            'message'=> 'success']);
    }   

    public function show(int $id): ResponseInterface{
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);


        if(empty($employee)){
            return $this->response->setStatusCode(404)
                                  ->setJSON(['message' => 'Data not found',
                                             'data' => null]) ;
        } 

        return $this->response->setJSON([ 
            'message'=> 'success',
            'data'=> $employee            
            ]);
    }

    public function update(int $id): ResponseInterface {
        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);

        if(empty($employee)){
            return $this->response->setStatusCode(404)
                                  ->setJSON(['message' => 'Data not found',
                                            'data' => null]);
        }

        $employeeModel->update($id, $this->request->getJSON());

        return $this->response->setJSON([
            'message' => 'success',
            'data' => null
        ]);
    }

    public function delete(int $id): ResponseInterface {
        $employeeModel = new EmployeeModel();
        $employeeModel->delete($id);
        return $this->response->setJSON([
            'message' => 'success',
            'data' => null
        ]);
    }
}
