<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Config\Database;

use App\Models\ProprietarioModel;
use App\Models\AuditoriaModel;

class Pessoas extends ResourceController
{
    protected $model;
protected $db;
protected $auditoria;
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

        $this->model = new ProprietarioModel();
        $this->db = Database::connect();
        $this->auditoria = new AuditoriaModel();
    }

    public function index()
    {
        $data = [
            'data' => $this->model->paginate()
        ];

        return $this->respond($data);
    }

    function pesquisar()
    {
        $nome = $this->request->getPost('nome');

        $data = $this->db->query("SELECT * FROM `pessoas` WHERE `nome` LIKE '$nome%'")->getResult();
        return $this->respond($data);
    }

    public function perfil($id)
    {
        $data = [
            'data' => $this->model->where('id', $id)->paginate()
        ];

        return $this->respond($data);
    }

    public function adicionar()
    {
        $data = json_decode(file_get_contents("php://input"));
        $hashedpassword = password_hash($data->password ? $data->password : 123456, PASSWORD_BCRYPT);
        $data = [
            'estado' => $data->estado,
            'tipo' => $data->tipo,
            'email' => $data->email,
            'password' => $hashedpassword,
            'autenticacao' => $data->autenticacao,
            'perfil' => $data->perfil,
            'ultimoAcesso' => $data->ultimoAcesso,
            'criadopor' => $data->criadopor,
            'prinome' => $data->prinome,
            'ultimonome' => $data->ultimonome,
            'telefone' => $data->telefone,
            'acesso' => $data->acesso
        ];

        helper('funcao');
        $data = cadastronormal($this->model, $data, $this->db, $this->auditoria, 'Nova Pessoa');
        return $this->respond($data);
    }

    public function editar()
    {
        $data = json_decode(file_get_contents("php://input"));
        $data = [
            'id' => $data->id,
            'estado' => $data->estado,
            'tipo' => $data->tipo,
            'email' => $data->email,
            'autenticacao' => $data->autenticacao,
            'perfil' => $data->perfil,
            'ultimoAcesso' => $data->ultimoAcesso,
            'criadopor' => $data->criadopor,
            'prinome' => $data->prinome,
            'ultimonome' => $data->ultimonome,
            'telefone' => $data->telefone,
            'acesso' => $data->acesso
        ];

        helper('funcao');
        $data = updatenormal($this->model, $data, $this->auditoria, 'Pessoa');
        return $this->respond($data);
    }

    public function eliminar($id)
    {
        $actor = $this->request->getPost('actor');
        helper('funcao');

        $data = daletarnormal($id, $this->db, 'pessoas', $actor, $this->auditoria, 'Pessoa');
        return $this->respond($data);
    }

    public function editar_password()
    {
        $data = json_decode(file_get_contents("php://input"));
        $hashedpassword = password_hash($data->new_password, PASSWORD_BCRYPT);
        $data = [
            'email' => $this->email,
            'old_password' => $this->old_password, 
            'new_password' => $this->new_password,
            'criadopor' => $this->criadopor,
        ];
        helper('funcao');

        $respond = passwordchange($data, $this->model, $this->db, $this->auditoria);
        return $this->respond($respond);
    }
}
