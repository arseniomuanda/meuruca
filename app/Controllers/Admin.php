<?php

namespace App\Controllers;

use App\Models\AuditoriaModel;
use App\Models\ContaModel;
use App\Models\ProdutoModel;
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
    protected $produtoModel;


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
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
                header("Access-Control-Request-Headers: Content-Type, Authorization");
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
        $this->produtoModel = new ProdutoModel();
        $this->contaModel = new ContaModel();

        $this->session = Services::session();
        $this->protect = new Login();
    }


    public function getDashboard()
    {
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

            if ($token) {
                $decoded = JWT::decode($token, $secret_key, array('HS256'));
                if ($decoded) {
                    if ($decoded->data->acesso > 1) {
                        return $this->respond([
                            'clientes' => $this->db->query("SELECT COUNT(*) total FROM proprietarios")->getRow(0)->total,
                            'lojas' => $this->db->query("SELECT COUNT(*) total FROM lojas")->getRow(0)->total,
                            'prestadores' => $this->db->query("SELECT COUNT(*) total FROM prestadors")->getRow(0)->total,
                            'servicos' => $this->db->query("SELECT COUNT(*) total FROM servicos")->getRow(0)->total,
                            'produtos' => $this->db->query("SELECT COUNT(*) total FROM produtos")->getRow(0)->total,
                            'viaturas' => $this->db->query("SELECT COUNT(*) total FROM viaturas")->getRow(0)->total,
                            'modelos' => $this->db->query("SELECT COUNT(*) total FROM modelos")->getRow(0)->total,
                            'marcas' => $this->db->query("SELECT COUNT(*) total FROM marcas")->getRow(0)->total,
                            'anos' => $this->db->query("SELECT COUNT(*) total FROM ano_fabricos")->getRow(0)->total,
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

    public function saveProduto($id = null)
    {
        try {
            $secret_key = $this->protect->privateKey();
            $token = null;
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            if (!$authHeader) return null;
            $arr = explode(" ", $authHeader);
            $token = $arr[1];
            $token_validate = $this->db->query("SELECT COUNT(*) total FROM `utilizadors` WHERE api_token = '$token'")->getRow(0)->total;

            if ($token_validate < 1) {
                return $this->respond([
                    'message' => 'Access denied',
                    'status' => 401,
                    'error' => true,
                    'type' => "Token não encontrado!"
                ], 403);
            }


            if ($token) {
                $decoded = JWT::decode($token, $secret_key, array('HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1) {
                        helper('funcao');
                        $data = $this->request->getPost();
                        $data['criadopor'] = $decoded->data->id;
                        $foto = $this->request->getFile('imagem');
                        $data = cleanarray($data);
                        if(is_null($id)){
                            $resposta = cadastrocomumafoto($this->produtoModel, $data, $this->db, $this->auditoriaModel, $foto, 'imagem');
                        }else {
                            $data['id'] = $id;
                            $resposta = updatecomumafoto($this->produtoModel, $data, $this->db, $this->auditoriaModel, 'Produto', $this->produtoModel->table, $foto, 'imagem');
                        }
                        
                        return $this->respond($resposta);
                    }
                }
            }
        } catch (\Throwable $th) {
            print_r($th);
            return $this->respond([
                'message' => 'Access denied',
                'status' => 401,
                'error' => true,
                'type' => "Token não encontrado!"
            ], 403);
        }
    }

    public function deletPedido($id = null)
    {
        try {
            $secret_key = $this->protect->privateKey();
            $token = null;
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            if (!$authHeader) return null;
            $arr = explode(" ", $authHeader);
            $token = $arr[1];
            $token_validate = $this->db->query("SELECT COUNT(*) total FROM `utilizadors` WHERE api_token = '$token'")->getRow(0)->total;

            if ($token_validate < 1) {
                return $this->respond([
                    'message' => 'Access denied',
                    'status' => 401,
                    'error' => true,
                    'type' => "Token não encontrado!"
                ], 403);
            }


            if ($token) {
                $decoded = JWT::decode($token, $secret_key, array('HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1) {
                        helper('funcao');
                        $data = $this->request->getPost();
                        $data['criadopor'] = $decoded->data->id;
                        
                        $resposta = eliminarPedido($id, $this->auditoriaModel, $decoded->data->id);

                        return $this->respond($resposta);
                    }
                }
            }
        } catch (\Throwable $th) {
            print_r($th);
            return $this->respond([
                'message' => 'Access denied',
                'status' => 401,
                'error' => true,
                'type' => "Token não encontrado!"
            ], 403);
        }
    }

    public function viaturas($id = null)
    {
        try {
            $secret_key = $this->protect->privateKey();
            $token = null;
            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');
            if (!$authHeader) return null;
            $arr = explode(" ", $authHeader);
            $token = $arr[1];
            $token_validate = $this->db->query("SELECT COUNT(*) total FROM `utilizadors` WHERE api_token = '$token'")->getRow(0)->total;

            if ($token_validate < 1) {
                return $this->respond([
                    'message' => 'Access denied',
                    'status' => 401,
                    'error' => true,
                    'type' => "Token não encontrado!"
                ], 403);
            }


            if ($token) {
                $decoded = JWT::decode($token, $secret_key, array('HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1) {
                        helper('funcao');
                        $resposta = $this->db->query("SELECT viaturas.*, modelos.nome modelo, marcas.nome marca, ano_fabricos.nome ano FROM viaturas INNER JOIN ano_fabricos ON viaturas.ano = ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo = modelos.id INNER JOIN marcas ON modelos.marca = marcas.id WHERE proprietario = $id")->getResult();
                        
                        return $this->respond($resposta);
                    }
                }
            }
        } catch (\Throwable $th) {
            print_r($th);
            return $this->respond([
                'message' => 'Access denied',
                'status' => 401,
                'error' => true,
                'type' => "Token não encontrado!"
            ], 403);
        }
    }
}
