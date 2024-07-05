<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use DateTime;

class PasswordResetController extends BaseController
{
    use ResponseTrait;

    public function requestReset(){
        $email  = "chiequchie@gmail.com";

        if(!empty($email)){
            $token = $this->encryptEmail($email);
            $kirimEmail = $this->sendEmail($email,$token);

            if(!$kirimEmail){
                return $this->respond(['status' => 'success',
                                        'message' => 'Check your ' . $email . ' for reset instructions',
                                        'token' => $token]);

            } else {
                return $this->fail('Failed to send email');
            }   
        }

        return $this->failNotFound('Email not registered');
    }

    public function encryptEmail($email){
        $secretKey = 'semangat45';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($email,'aes-256-cbc', $secretKey, 0, $iv);
        $token = base64_encode($encrypted . '::' . $iv);
        return $token;
    }

    public function decryptToken($token){
        $secretKey = 'semangat45';
        list($encrypted_data, $iv) = explode('::', base64_decode($token), 2);
        $email = openssl_decrypt($encrypted_data,'aes-256-cbc', $secretKey,0, $iv);
        return $email;
    }

    private function sendEmail(string $email,$token){
        $emailService = Services::email();
        $emailService->setFrom('sn.astini@gmail.com','Hacktiv8 Training Program');
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Request');
        $emailService->setMessage(view('password_reset', ['token' => $token]));
        $emailService->setMailType('html');

        if(!$emailService->send()){
            $data = $emailService->printDebugger(['headers', 'subject', 'body']);
            log_message('error', 'Email failed to send: ' . print_r($data, true));
            return false;
        }

    
        return true;
    }

    public function sendResetLink(){
        $payload = $this->request->getJSON();
        $user =  new UserModel();
        $userData = $user->where('email', $payload->email)->first();

        if(!$userData){
            return $this->response->setJSON(
                [
                    'message' => 'failed',
                    'data' => 'User Not Found'
                ]
            );
        }

        $token = $this->encryptEmail($userData['email']);
        $expirataionTime = new DateTime();
        $exp = $expirataionTime->modify('+1 day')->format('Y-m-d H:i:s');

        $user->update($userData['id'], [
            'password_reset_token' => $token,
            'password_reset_token_expiration' => $exp
        ]);

        $sendEmail = $this->sendEmail($userData['email'], $token);
        if(!$sendEmail){
            return $this->response->setJSON([
                'message'=> 'failed',
                'data'=> 'Email failed to send'
            ])->setStatusCode(400);
        }

        return $this->response->setJSON([
            'message'=> 'success',
            'data'=> 'Email Sent'
            ]);

    }

    public function resetPassword() {
        $payload = $this->request->getJSON();
        $token = $payload->token;
        $password = $payload->password;
        $confirmPassword = $payload->confirm_password;

        $this->validate([
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]'
        ]);

        $user = new UserModel();
        $decryptedTokenEmail = $this->decryptToken($token);

        $userData = $user->where('email', $decryptedTokenEmail)
                        ->where('password_reset_token', $token)
                        ->first();

        if(!$userData){
            return $this->response->setJSON([
                'message'=> 'failed',
                'data'=> 'Invalid token'
                ])->setStatusCode(400);
        }

        $now = new DateTime();

        if($now > new DateTime($userData['password_reset_token_expiration'])){
            return $this->response->setJSON([
                'message'=> 'failed',
                'data'=> 'Token expired'
                ])->setStatusCode(0);
        }
        
        $password = password_hash($password, PASSWORD_BCRYPT);

        $user->update($userData['id'], [
            'password' => $password,
            'password_reset_token' => null,
            'password_reset_token_expiration'=> null
        ]);

        return $this->response->setJSON([
            'message'=> 'success',
            'data'=> null
            ]);
        
        }
   
}
