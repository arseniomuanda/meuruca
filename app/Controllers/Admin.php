<?php

namespace App\Controllers;

use App\Models\AuditoriaModel;
use App\Models\ContaModel;
use App\Models\ProprietarioModel;
use App\Models\UtilizadorModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use Config\Services;
use Firebase\JWT\JWT;

class Admin extends ResourceController
{

    protected $db;
    protected $utilizadorModel;
    protected $auditoriaModel;
    protected $proprietarioModel;
    protected $contaModel;
    protected $session;
    protected $protect;


    public function __construct()
    {
        // headers
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit(0);
        }

        $this->db = Database::connect();
        $this->utilizadorModel = new UtilizadorModel();
        $this->auditoriaModel = new AuditoriaModel();
        $this->proprietarioModel = new ProprietarioModel();
        $this->contaModel = new ContaModel();

        $this->session = Services::session();
        $this->protect = new Login();
    }


    public function getDashboard(){
        try {
            $secret_key = $this->protect->privateKey();
            $token = null;
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            if (!$authHeader) return null;
            $arr = explode(" ", $authHeader);
            $token = $arr[1];
            $token_validate = $this->db->query("SELECT COUNT(*) total FROM `utilizadors` WHERE api_token = '$token'")->getRow(0)->total;
            if ($token_validate < 1)
                return $this->respond([
                    'message' => 'Access denied',
                    'status' => 401,
                    'error' => true,
                    'type' => "Token não encontrado!"
                ]);

            if ($token){
                $decoded = JWT::decode($token, $secret_key, array('HS256'));
                if ($decoded) {
                    if($decoded->data->acesso > 1){
                        return $this->respond([
                            'clientes',
                            'lojas',
                            'prestadores',
                            'serviços',
                            'carros',
                            'modelos',
                            'marcas',
                            'anos',
                        ]);
                    }
                }
            }

        } catch (\Throwable $th) {
            return $this->respond([
                'message' => 'Access denied',
                'status' => 401,
                'error' => true,
                'type' => "Token não encontrado!"
            ]);
        }
    }
}
