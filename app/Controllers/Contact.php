<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmployeeModel;

class Contact extends ResourceController
{
    public function index()
    {
        echo '# code...';
    }

    public function sendMail()
    {
echo "ss";
        $email = \Config\Services::email();

        $emissorEmail = $this->request->getPost('email');
        $emissorName = $this->request->getPost('name');
        $emissorSubject = $this->request->getPost('subject');
        $emissorMessage = $this->request->getPost('message');

        $email->setFrom($emissorEmail, 'Costumer - ' . $emissorName);
        $email->setTo("info@meuruca.ao");
        // $email->setCC('operations@goodelivering.com');
        // $email->setBCC('paulocazo@goodelivering.com');

        $email->setSubject($emissorSubject);
        $email->setMessage($emissorMessage);
        if ($email->send()) {
            return $this->respond([
                'message' => 'OK'
            ], 200);
        } else {
            return $this->respond([
                $email,
                'message' => 'Bab'
            ], 503);
        }
    }
}
