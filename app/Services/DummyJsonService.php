<?php   

namespace App\Services;
use CodeIgniter\Config\Services;
use Exception;

class DummyJsonService{
    protected $client;

    public function __construct(){
        $this->client = Services::curlrequest([
            "baseURI" => "https://dummyjson.com",
            "http_errors"=> false,
        ]);

        $this->login();
    }

    public function getCarts(){
        $response = $this->client->get("auth/carts", [
            "headers"=> [
                "Content-Type"=> "application/json",
            ]]);
        if($response->getStatusCode() == 200){
            return json_decode($response->getBody(), true);
        } 

        throw new Exception("Failed to get carts", $response->getStatusCode());
    }



    private function login(){
        $response = $this->client->post('/auth/login',[
            'headers'=> [
                'Content_type'=> 'application/json',
            ],
            'json'=> [
                'username'=> 'emilys',
                'password'=> 'emilyspass',
                'expiresInMins'=>60,
            ]
            ]);

        if($response->getStatusCode() == 200){
            $body = json_decode($response->getBody());
            $this->client->addHeader('Authorization','Bearer' . $body->token);
        }

        throw new Exception('Failed to login', $response->getStatusCode());
    

    }
}