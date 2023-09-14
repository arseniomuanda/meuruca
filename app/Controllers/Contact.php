<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmployeeModel;
use Exception;

class Contact extends ResourceController
{

    public function sendMail()
    {
        try {
            $email = \Config\Services::email();

            $emissorEmail = $this->request->getPost('email');
            $emissorName = $this->request->getPost('name');
            $emissorSubject = $this->request->getPost('subject');
            $emissorMessage = $this->request->getPost('message');
            $receptor = $this->request->getPost('receptor');
            $file = $this->request->getFile('file');

            if (($file != null)) {
                $email->attach($file->getExtension(), $file->getBasename());
            }
            $email->setFrom($receptor, $emissorName);
            $email->setTo($receptor);

            $email->setSubject($emissorSubject);
            $email->setMessage($emissorMessage . '<br>De: ' . $emissorEmail);

            if ($email->send()) {
                echo 'OK';
                return;
            } else {
                return $this->respond([
                    'message' => $email->printDebugger(['headers'])
                ], 501);
            }
        } catch (\Throwable $th) {
            return $this->respond([
                'message' => 'Erro',
                'error' => $th->getMessage()
            ], 501);
        }
    }

    public function teste()
    {

        $newDueDate = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime(date('Y-m-d H:i:s'))));

        $token_before_hash = 'manomuanda@gmail.com' . 1 . $newDueDate;
        $token = password_hash($token_before_hash, PASSWORD_BCRYPT);


        $data = [
            'email' => 'manomuanda@gmail.com',
            'token' => 'dsflkdsflkjgskjlskgjd',
            'tempo' =>  $newDueDate
        ];

        return view('templates/confirmemail', $data);
    }
}
