<?php

namespace App\Controllers;

use App\Models\AgendaModel;
use App\Models\AnofabricoModel;
use App\Models\AuditoriaModel;
use App\Models\ContaModel;
use App\Models\FacturaModel;
use App\Models\ItemfacturaModel;
use App\Models\MarcaModel;
use App\Models\ModeloModel;
use App\Models\PrestadorModel;
use App\Models\ProdutoModel;
use App\Models\ProprietarioModel;
use App\Models\ServicoModel;
use App\Models\UtilizadorModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use Config\Services;
use Firebase\JWT\JWT;
use phpDocumentor\Reflection\Types\This;

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
    protected $agendaModel;
    protected $facturaModel;
    protected $itemfacturaModel;
    protected $prestadorModel;
    protected $servicoModel;
    protected $marcaModel;
    protected $modeloModel;
    protected $anoModel;


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
        $this->agendaModel = new AgendaModel();
        $this->utilizadorModel = new UtilizadorModel();
        $this->auditoriaModel = new AuditoriaModel();
        $this->proprietarioModel = new ProprietarioModel();
        $this->produtoModel = new ProdutoModel();
        $this->contaModel = new ContaModel();
        $this->facturaModel = new FacturaModel();
        $this->itemfacturaModel = new ItemfacturaModel();
        $this->prestadorModel = new PrestadorModel();
        $this->servicoModel = new ServicoModel();
        $this->marcaModel = new MarcaModel();
        $this->modeloModel = new ModeloModel();
        $this->anoModel = new AnofabricoModel();

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
                        if (is_null($id)) {
                            $resposta = cadastrocomumafoto($this->produtoModel, $data, $this->db, $this->auditoriaModel, $foto, 'imagem');
                        } else {
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

    public function deletPedido($id)
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

    public function newAgendamento()
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
                        $factura = null;
                        $agendaData = [
                            'proprietario' => $this->request->getPost('proprietario'),
                            'inicio' => $this->request->getPost('inicio'),
                            'descricao' => $this->request->getPost('descricao'),
                            'viatura' => $this->request->getPost('viatura'),
                            'prestador' => $this->request->getPost('prestador'),
                            'is_domicilio' => $this->request->getPost('is_domicilio'),
                            'servico_entrega' => $this->request->getPost('servico_entrega'),
                            'categoria' => $this->request->getPost('categoria'),
                            'criadopor' => $decoded->data->id,
                            'endereco' => $this->request->getPost('endereco') ? $this->request->getPost('endereco') : '',
                            'latitude' => $this->request->getPost('latitude') ? $this->request->getPost('latitude') : '',
                            'longitude' => $this->request->getPost('longitude') ? $this->request->getPost('longitude') : '',
                            'table' => 'agenda',
                        ];

                        $agendaData = cleanarray($agendaData);

                        $agenda = cadastronormal($this->agendaModel, $agendaData, $this->db, $this->auditoriaModel);

                        if ($agenda['code'] == 200) {
                            $facturaData = [
                                'agenda' => $agenda['id'],
                                'proprietario' => $this->request->getPost('proprietario'),
                                'criadopor' => $decoded->data->id,
                                'datafactura' => date('Y-m-d'),
                                'conta' => $decoded->data->conta,
                                'table' => 'factura',
                            ];

                            $factura = cadastronormal($this->facturaModel, $facturaData, $this->db, $this->auditoriaModel);

                            $this->db->query("UPDATE `agendas` SET `factura` = " . $factura['id'] . " WHERE `id` = " . $agenda['id']);

                            $servico_entrega = $this->request->getPost('servico_entrega');
                            $is_domicilio = $this->request->getPost('is_domicilio');

                            if ($factura['code'] == 200) {
                                if (($servico_entrega == 1) || ($is_domicilio == 1)) {
                                    $itemRow = $this->db->query("SELECT * FROM `servicos` WHERE prestador = 2 AND categoria = 3")->getRow(0);

                                    $itemData = [
                                        'factura' => $factura['id'],
                                        'valor' => $this->request->getPost('preco_distancia') ? $this->request->getPost('preco_distancia') : $itemRow->valor,
                                        'criadopor' => $decoded->data->id,
                                        'nome' => $itemRow->nome,
                                        'conta' => $decoded->data->conta,
                                        'qntidade' => 1,
                                        'table' => 'itemfactura',
                                    ];

                                    cadastronormal($this->itemfacturaModel, $itemData, $this->db, $this->auditoriaModel);
                                }/*  else {
                    deletarnormal($factura, $db, $this->facturaModel, $auditoria);
                    deletarnormal($agenda, $db, $this->agendaModel, $auditoria);
                } */
                            } else {
                                deletarnormal($factura, $this->db, $this->facturaModel, $this->auditoriaModel);
                                deletarnormal($agenda, $this->db, $this->agendaModel, $this->auditoriaModel);
                            }
                        }

                        return $this->respond($agenda);
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

    public function newPrestador()
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


                        $foto = $this->request->getFile('foto');

                        $data = [

                            'nome' => $this->request->getPost('nome'),
                            'nif' => $this->request->getPost('nif'),
                            'email' => $this->request->getPost('email'),
                            'telefone' => $this->request->getPost('telefone'),
                            'endereco' => $this->request->getPost('endereco'),
                            'criadopor' => $this->request->getPost('criadopor'),
                            'site' => $this->request->getPost('site'),
                            'androidlink' => $this->request->getPost('androidlink'),
                            'ioslink' => $this->request->getPost('ioslink'),
                            'gps_latitude' => $this->request->getPost('gps_latitude'),
                            'gps_longitude' => $this->request->getPost('gps_longitude'),
                            'w3w' => $this->request->getPost('w3w'),
                            'country' => $this->request->getPost('country'),
                            'provincia' => $this->request->getPost('provincia'),
                            'municipio' => $this->request->getPost('municipio'),
                            'distrito' => $this->request->getPost('distrito'),
                            'comuna' => $this->request->getPost('comuna'),
                            'bairro' => $this->request->getPost('bairro'),
                            'n_casa' => $this->request->getPost('n_casa'),
                            'tipo' => $this->request->getPost('tipo'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = cadastrocomumafoto($this->prestadorModel, $data, $this->db, $this->auditoriaModel, $foto, 'foto');

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


    public function editAgendamento($id)
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


                        $agendaData = [
                            'id' => $id,
                            'proprietario' => $this->request->getPost('proprietario'),
                            'inicio' => $this->request->getPost('data_pedido'),
                            'descricao' => $this->request->getPost('descricao'),
                            'criadopor' => $decoded->data->id,
                            'table' => 'agenda',
                        ];

                        $agendaData = cleanarray($agendaData);

                        //return $this->respond($agendaData);

                        $resposta = updatenormal($this->agendaModel, $agendaData, $this->auditoriaModel);
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


    public function editPrestador($id)
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


                        $foto = $this->request->getFile('foto');

                        $data = [
                            'id' => $id,
                            'nome' => $this->request->getPost('nome'),
                            'nif' => $this->request->getPost('nif'),
                            'email' => $this->request->getPost('email'),
                            'telefone' => $this->request->getPost('telefone'),
                            'endereco' => $this->request->getPost('endereco'),
                            'criadopor' => $this->request->getPost('criadopor'),
                            'site' => $this->request->getPost('site'),
                            'androidlink' => $this->request->getPost('androidlink'),
                            'ioslink' => $this->request->getPost('ioslink'),
                            'gps_latitude' => $this->request->getPost('gps_latitude'),
                            'gps_longitude' => $this->request->getPost('gps_longitude'),
                            'w3w' => $this->request->getPost('w3w'),
                            'country' => $this->request->getPost('country'),
                            'provincia' => $this->request->getPost('provincia'),
                            'municipio' => $this->request->getPost('municipio'),
                            'distrito' => $this->request->getPost('distrito'),
                            'comuna' => $this->request->getPost('comuna'),
                            'bairro' => $this->request->getPost('bairro'),
                            'n_casa' => $this->request->getPost('n_casa'),
                            'tipo' => $this->request->getPost('tipo'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = updatecomumafoto($this->prestadorModel, $data, $this->db, $this->auditoriaModel, 'Prestador', $this->prestadorModel->table, $foto, 'foto');

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

    public function newServico()
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

                        $data = [
                            'prestador' => $this->request->getPost('prestador'),
                            'nome' => $this->request->getPost('nome'),
                            'valor' => $this->request->getPost('valor'),
                            'descricao' => $this->request->getPost('descricao'),
                            'is_aprovado' => $this->request->getPost('is_aprovado'),
                            'is_domicilio' => $this->request->getPost('is_domicilio'),
                            'categoria' => $this->request->getPost('categoria'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = cadastronormal($this->servicoModel, $data, $this->db, $this->auditoriaModel);

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

    public function newMarca()
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

                        $foto = $this->request->getFile('foto');

                        $data = [
                            'nome' => $this->request->getPost('nome'),
                            'descricao' => $this->request->getPost('descricao'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = cadastrocomumafoto($this->marcaModel, $data, $this->db, $this->auditoriaModel, $foto, 'foto');

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

    public function editMarca($id)
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

                        $foto = $this->request->getFile('foto');

                        $data = [
                            'id' => $id,
                            'nome' => $this->request->getPost('nome'),
                            'descricao' => $this->request->getPost('descricao'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = updatecomumafoto($this->marcaModel, $data, $this->db, $this->auditoriaModel, 'Marca', $this->marcaModel->table, $foto, 'foto');

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

    public function newModelo()
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

                        $foto = $this->request->getFile('foto');

                        $data = [
                            'nome' => $this->request->getPost('nome'),
                            'marca' => $this->request->getPost('marca'),
                            'descricao' => $this->request->getPost('descricao'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = cadastrocomumafoto($this->modeloModel, $data, $this->db, $this->auditoriaModel, $foto, 'foto');

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

    public function editModelo($id)
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

                        $foto = $this->request->getFile('foto');

                        $data = [
                            'id' => $id,
                            'nome' => $this->request->getPost('nome'),
                            'marca' => $this->request->getPost('marca'),
                            'descricao' => $this->request->getPost('descricao'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = updatecomumafoto($this->modeloModel, $data, $this->db, $this->auditoriaModel, 'Modelo', $this->modeloModel->table, $foto, 'foto');

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

    public function newAno()
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

                        $foto = $this->request->getFile('foto');

                        $data = [
                            'nome' => $this->request->getPost('nome'),
                            'modelo' => $this->request->getPost('modelo'),
                            'descricao' => $this->request->getPost('descricao'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = cadastrocomumafoto($this->anoModel, $data, $this->db, $this->auditoriaModel, $foto, 'foto');

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

    public function editAno($id)
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

                        $foto = $this->request->getFile('foto');

                        $data = [
                            'id' => $id,
                            'nome' => $this->request->getPost('nome'),
                            'modelo' => $this->request->getPost('modelo'),
                            'descricao' => $this->request->getPost('descricao'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = updatecomumafoto($this->anoModel, $data, $this->db, $this->auditoriaModel, 'Modelo', $this->anoModel->table, $foto, 'foto');

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

    public function editServico(int $id)
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

                        $data = [
                            'id' => $id,
                            'prestador' => $this->request->getPost('prestador'),
                            'nome' => $this->request->getPost('nome'),
                            'valor' => $this->request->getPost('valor'),
                            'descricao' => $this->request->getPost('descricao'),
                            'is_aprovado' => $this->request->getPost('is_aprovado'),
                            'is_domicilio' => $this->request->getPost('is_domicilio'),
                            'categoria' => $this->request->getPost('categoria'),
                            'criadopor' => $decoded->data->id
                        ];

                        $data = cleanarray($data);

                        $resposta = updatenormal($this->servicoModel, $data, $this->auditoriaModel);

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

    public function deletServico($id)
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

                        $data = [
                            'id' => $id,
                            'criadopor' => $decoded->data->id
                        ];

                        $resposta = deletarnormal($data, $this->db, $this->servicoModel, $this->auditoriaModel);

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
