<?php

namespace App\Controllers;

use App\Models\AnofabricoModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\AgendaimagemModel;
use App\Models\AgendaModel;
use App\Models\AuditoriaModel;
use App\Models\CaracteristicaModel;
use App\Models\CarrinhoModel;
use App\Models\CategoriaModel;
use App\Models\ContactoModel;
use App\Models\ContaModel;
use App\Models\EnderecoModel;
use App\Models\FacturaModel;
use App\Models\GestaoviaturaModel;
use App\Models\ItemfacturaModel;
use App\Models\LojaModel;
use App\Models\MarcaModel;
use App\Models\ModeloModel;
use App\Models\OleogestaoviaturaModel;
use App\Models\PrestadorModel;
use App\Models\ProdutoModel;
use App\Models\ProprietarioModel;
use App\Models\ServicoModel;
use App\Models\TipocontaModel;
use App\Models\TipoitemModel;
use App\Models\UtilizacaoModel;
use App\Models\UtilizadorModel;
use App\Models\ViaturaModel;
use App\Models\SeguroModel;
use App\Models\SinistroModel;
use App\Models\AvaliacaoModel;

use function PHPSTORM_META\type;

class Operations extends ResourceController
{
    protected $agendaModel;
    protected $anofabricoModel;
    protected $auditoriaModel;
    protected $contactoModel;
    protected $contaModel;
    protected $enderecoModel;
    protected $facturaModel;
    protected $gestaoviaturaModel;
    protected $itemfacturaModel;
    protected $marcaModel;
    protected $modeloModel;
    protected $olegestaoviaturaModel;
    protected $proprietarioModel;
    protected $tipocontaModel;
    protected $tipoitemModel;
    protected $utilizacaoModel;
    protected $utilizadorModel;
    protected $viaturaModel;
    protected $servicoModel;
    protected $prestadorModel;
    protected $seguroModel;
    protected $sinistroModel;
    protected $agendaimagemModel;
    protected $emergenciaModel;
    protected $caracteristicaModel;
    protected $avaliacaoModel;

    protected $lojaModel;
    protected $categoriaModel;
    protected $produtoModel;
    protected $carrinhoModel;

    protected $db;
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

        /* Aqui vão todos os models */
        $this->agendaModel = new AgendaModel();
        $this->anofabricoModel = new AnofabricoModel();
        $this->auditoriaModel = new AuditoriaModel();
        $this->contactoModel = new ContactoModel();
        $this->contaModel = new ContaModel();
        $this->enderecoModel = new EnderecoModel();
        $this->facturaModel = new FacturaModel();
        $this->gestaoviaturaModel = new GestaoviaturaModel();
        $this->itemfacturaModel = new ItemfacturaModel();
        $this->marcaModel = new MarcaModel();
        $this->modeloModel = new ModeloModel();
        $this->olegestaoviaturaModel = new OleogestaoviaturaModel();
        $this->proprietarioModel = new ProprietarioModel();
        $this->tipocontaModel = new TipocontaModel();
        $this->tipoitemModel = new TipoitemModel();
        $this->utilizacaoModel = new UtilizacaoModel();
        $this->utilizadorModel = new UtilizadorModel();
        $this->viaturaModel = new ViaturaModel();
        $this->servicoModel = new ServicoModel();
        $this->prestadorModel = new PrestadorModel();
        $this->seguroModel = new SeguroModel();
        $this->sinistroModel = new SinistroModel();
        $this->agendaimagemModel = new AgendaimagemModel();
        $this->avaliacaoModel = new AvaliacaoModel();

        $this->lojaModel = new LojaModel();
        $this->categoriaModel = new CategoriaModel();
        $this->produtoModel = new ProdutoModel();
        $this->carrinhoModel = new CarrinhoModel();
        $this->caracteristicaModel = new CaracteristicaModel();

        $this->db = Database::connect();
        $this->protect = new Login();
    }

    /**
     * Aque sao definidas todas as requisiçoes simples como insert,
     * update, delete and show...
     * 
     * ['tabela', 'dados', ...]
     */
    public function index()
    {
        try {
            $secret_key = $this->protect->privateKey();

            $token = null;

            $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');

            if (!$authHeader) {
                return null;
            }

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
        } catch (\Throwable $th) {
            return $this->respond([
                'message' => 'Access denied',
                'status' => 401,
                'error' => true,
                'type' => "Token não encontrado!"
            ]);
        }

        if ($token) {
            try {
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));
                // return $this->respond($decoded);
                // Access is granted. Add code of the operation here
                if ($decoded) {

                    helper('funcao');
                    $data = $this->request->getPost();
                    $data['proprietario'] = $decoded->data->proprietario;
                    $data['criadopor'] = $decoded->data->id;
                    $data['utilizador'] = $decoded->data->id;

                    $option = $this->request->getPost('option');
                    if (isset($data['table']))
                        $model = $this->chooseModel($data['table']);
                    else
                        return returnVoid($data, 400);
                    switch ($option) {
                        case 'newCar':
                            # code...
                            $response = cadastronormal($model, $data, $this->db, $this->auditoriaModel);
                            /* Alem de selecionar a model tenho tambem de selecionar o clear arrey */
                            break;
                        case 'insert':
                            # code...
                            $response = cadastronormal($model, $data, $this->db, $this->auditoriaModel);
                            /* Alem de selecionar a model tenho tambem de selecionar o clear arrey */
                            break;
                        case 'insertImg':
                            # code...
                            //Especialmente para codastrar imagens os productos
                            $foto = $this->request->getFile('imagem');
                            $response = cadastrocomumafoto($model, $data, $this->db, $this->auditoriaModel, $foto, 'imagem');
                            /* Alem de selecionar a model tenho tambem de selecionar o clear arrey */
                            break;
                        case 'update':
                            # code...
                            $response = updatenormal($model, $data, $this->auditoriaModel);
                            break;
                        case 'delete':
                            # code...
                            $response = deletarnormal($data, $this->db, $model, $this->auditoriaModel);
                            break;
                        case 'deleteItemAgenda':
                            # code...
                            $response = deletarItemAgenda($data, $this->db, $model, $this->auditoriaModel);
                            break;
                        case 'show':
                            # code...
                            $response = $model->paginate(1000);
                            break;
                        case 'perfil':
                            # code...
                            $id = $data['id'];
                            $response = $this->db->query("SELECT * FROM $model->table WHERE id = $id")->getRow(0);
                            break;
                        case 'carProfile':
                            # code...
                            $response = $this->carPorfile($data['id'], $this->db, $data['proprietario']);
                            break;
                        case 'prestadorProfile':
                            # code...
                            $id = $data['id'];
                            $response = [
                                'data' => $this->db->query("SELECT * FROM $model->table WHERE id = $id")->getRow(0),
                                'services' => $this->prestadorServices($data['id'], $this->servicoModel),
                                'code' => 200
                            ];
                            return $this->respond($response);
                        case 'userProfile':
                            # code...
                            $response = $this->userPorfile($data['user'], $this->db, $data['proprietario']);
                            break;
                        case 'changeUserProfile':
                            # code...
                            $response = $this-> changeUserProfile($data['utilizador'], $data['proprietario'], $data, $model, $this->request->getFile('foto'));
                            break;
                        case 'agendaProfile':
                            # code...
                            $id = $data['id'];
                            if ($this->db->query("SELECT COUNT(*) total FROM agendas WHERE id = $id")->getRow(0)->total < 1) {
                                $response = returnVoid($data, 'Item não encontrado!');
                                return $this->respond($response);
                            }
                            $agenda = $this->db->query("SELECT * FROM perfil_agenda WHERE id = $id")->getRow(0);
                            $factura = $this->db->query("SELECT * FROM facturas WHERE agenda = $id")->getRow(0);
                            $response = [
                                'data' => $agenda,
                                'itens' => $this->db->query("SELECT * FROM itemfacturas WHERE factura = $factura->id")->getResult(),
                                'imagens' => $this->db->query("SELECT * FROM agendaimages WHERE agenda = $id")->getResult(),
                                'code' => 200
                            ];
                            return $this->respond($response);
                            break;
                        case 'carGestaoPorfile':
                            # code...
                            $response = $this->gestaoViaturaPerfil($data['id'], $this->db, $data['proprietario']);
                            break;
                        case 'showModelos':
                            # code...
                            $response = $model->where('marca', $data['marca'])->paginate();
                            break;
                        case 'showAnos':
                            # code...
                            $response = $model->where('modelo', $data['modelo'])->paginate();
                            break;
                        case 'showPestadores':
                            # code...
                            $response = $this->getPrestadores($this->db, $model);
                        case 'showPagamentos':
                            # code...
                            $response = $this->db->query("SELECT facturas.*, proprietarios.id proprietario, proprietarios.nome, utilizadors.email, utilizadors.telefone, (SELECT SUM(valor * qntidade) FROM `itemfacturas` WHERE factura = facturas.id) AS total FROM `facturas` INNER JOIN proprietarios ON facturas.proprietario = proprietarios.id INNER JOIN utilizadors ON proprietarios.id = utilizadors.proprietario WHERE facturas.estado <> 3 AND proprietarios.id = ". $data['proprietario'] . " ORDER BY facturas.estado")->getResultArray();
                            break;
                        case 'arquivarPagamento':
                            # code...
                            $response = $this->db->query("UPDATE facturas SET estado = 3 WHERE id = " . $data['factura']);
                            break;
                        case 'newAgendamento':
                            # code...
                            $response = $this->newAgendamento($model, $data, $this->db, $this->auditoriaModel);
                            break;
                        case 'newAgendamentoItem':

                            $response = $this->addAgendamentoItem($model, $data, $data['servico'], $data['agenda']);
                            break;
                        case 'newAgendaImagem':
                            $response = $this->addAgendaImagem($model, $data);
                            break;
                        case 'searcheCar':
                            # code...
                            $response = $this->searcheCar($data['q'], $this->db, $data['proprietario']);
                            break;
                        case 'showUserCar':
                            # code...
                            $response = $this->showUserCar($this->db, $data['proprietario'], $model);
                            break;
                        case 'getAgendaNotifications':
                            # code...
                            $response = $this->getAgendaNotifications($this->db, $data['proprietario']);
                            break;
                            //ainda nao trabalhei nessas funçoes
                        case 'buscarProdutos':
                            # code...
                            $response = $this->getProducts();
                            break;
                        case 'buscarCategorias':
                            # code...
                            $response = $model->paginate();
                            break;
                        case 'buscarLojas':
                            # code...
                            $response = $model->paginate();
                            break;
                        case 'addItemCarrinho':
                            # code...
                            $response = $this->addCarrinho($model, $data);
                            break;
                        case 'removerItemCarrinho':
                            # code...
                            $response = $this->remCarrinho($model, $data);
                            break;
                        case 'limparCarrinho':
                            # code...
                            $response = $this->limparCarrinho($model, $data);
                            break;
                        default:
                            # code...
                            $response = returnVoid($data, 400);
                            break;
                    }
                    if (is_array($response))
                        $code = isset($response['code']) ? $response['code'] : 200;
                    else
                        $code = isset($response->code) ? $response->code : 200;

                    return $this->respond($response, $code);
                }
            } catch (\Exception $e) {
                $output = [
                    'message' => 'Access denied ',
                    'status' => 401,
                    'error' => true,
                    'type' => $e->getMessage()
                ];

                return $this->respond($output, 401);
            }
        }
    }


    public function checkOut()
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
        } catch (\Throwable $th) {
            return $this->respond([
                'message' => 'Access denied',
                'status' => 401,
                'error' => true,
                'type' => "Token não encontrado!"
            ]);
        }

        if ($token) {
            try {
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));
                // return $this->respond($decoded);
                // Access is granted. Add code of the operation here
                if ($decoded) {

                    helper('funcao');
                    $user = $this->request->getPost();
                    $decoded->data->proprietario;
                    $decoded->data->id;

                    $data = json_decode(file_get_contents("php://input"));

                    $factura = cadastronormal($this->facturaModel, [
                        'proprietario' => $decoded->data->proprietario,
                        'criadopor' => $decoded->data->id,
                        'datafactura' => date('Y-m-d'),
                        'conta' => $decoded->data->conta,
                    ], $this->db, $this->auditoriaModel);

                    $idFactura = $factura['id'];

                    foreach ($data as $value) {
                        $itemFactura = [
                            'factura' => $idFactura,
                            'valor' => isset($value->preco) ? $value->preco : null,
                            'criadopor' => $decoded->data->id,
                            'nome' => isset($value->nome) ? $value->nome : null,
                            'conta' => $decoded->data->conta,
                            'qntidade' => isset($value->quantidade) ? $value->quantidade : null,
                            'itemId' => isset($value->id) ? $value->id : null,
                        ];
                        cadastronormal($this->itemfacturaModel, $itemFactura, $this->db, $this->auditoriaModel);
                    }
                    $data = [
                        'pagamento' => $this->facturaModel->where('id', $idFactura)->first(),
                        'itens' => $this->itemfacturaModel->where('factura', $idFactura)->paginate()
                    ];

                    return $this->respond($data);
                }
            } catch (\Exception $e) {
                $output = [
                    'message' => 'Access denied ',
                    'status' => 401,
                    'error' => true,
                    'type' => $e->getMessage()
                ];

                return $this->respond($output, 401);
            }
        }
    }


    public function finalizarAgendamento()
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
        } catch (\Throwable $th) {
            return $this->respond([
                'message' => 'Access denied',
                'status' => 401,
                'error' => true,
                'type' => "Token não encontrado!"
            ]);
        }

        if ($token) {
            try {
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));
                // return $this->respond($decoded);
                // Access is granted. Add code of the operation here
                if ($decoded) {

                    $data = json_decode(file_get_contents("php://input"));
                
                    helper('funcao');

                    $factura = null;
                    $agendaData = [
                        'inicio' => isset($data->agenda->data_pedido) ? $data->agenda->data_pedido : $data->agenda->categoria,
                        'data_pedido' => isset($data->agenda->data_pedido) ? $data->agenda->data_pedido : $data->agenda->categoria,
                        'descricao' => isset($data->agenda->descricao) ? $data->agenda->descricao : null,
                        'viatura' => isset($data->agenda->viatura) ? $data->agenda->viatura : null,
                        'prestador' => isset($data->agenda->prestador) ? $data->agenda->prestador : null,
                        'is_domicilio' => isset($data->agenda->is_domicilio) ? $data->agenda->is_domicilio : null,
                        'servico_entrega' => isset($data->agenda->servico_entrega) ? $data->agenda->servico_entrega : null,
                        'categoria' => isset($data->agenda->categoria) ? $data->agenda->categoria : null,
                        'criadopor' => isset($decoded->data->id) ? $decoded->data->id : null,
                        'address' => isset($data->agenda->endereco) ? $data->agenda->endereco : null,
                        'latitude' => isset($data->agenda->latitude) ? $data->agenda->latitude : null,
                        'longitude' => isset($data->agenda->longitude) ? $data->agenda->longitude : null,
                        'criadopor' => $decoded->data->id,
                        'table' => 'Agenda'
                    ];

                    $agendaData = cleanarray($agendaData);

                    $agenda = cadastronormal($this->agendaModel, $agendaData, $this->db, $this->auditoriaModel);

                    if (!$agenda) {
                        return $this->respond($agenda, 501);
                    }

                    if ($agenda['code'] == 200) {
                        $facturaData = [
                            'agenda' => $agenda['id'],
                            'proprietario' => $decoded->data->proprietario,
                            'criadopor' => $decoded->data->id,
                            'datafactura' => date('Y-m-d'),
                            'conta' => $decoded->data->conta,
                            'table' => 'factura',
                        ];

                        $factura = cadastronormal($this->facturaModel, $facturaData, $this->db, $this->auditoriaModel);

                        if ($factura['code'] == 200) {
                            $this->db->query("UPDATE `agendas` SET `factura` = " . $factura['id'] . " WHERE `id` = " . $agenda['id']);
                            if ($data->agenda->servico_entrega == 1) {

                                $itemData = [
                                    'factura' => $factura['id'],
                                    'valor' => $data->agenda->preco_distancia,
                                    'criadopor' => $decoded->data->id,
                                    'nome' => 'Serviço de entrega',
                                    'conta' => $decoded->data->conta,
                                    'qntidade' => 1,
                                    'table' => 'itemfactura',
                                ];

                                $row = cadastronormal($this->itemfacturaModel, $itemData, $this->db, $this->auditoriaModel);
                                if (!$row) {
                                    return $this->respond($row, 501);
                                }
                            }

                            foreach ($data->items as $value) {
                                $itemFactura = [
                                    'factura' => $factura['id'],
                                    'valor' => isset($value->valor) ? $value->valor : null,
                                    'criadopor' => $decoded->data->id,
                                    'nome' => isset($value->nome) ? $value->nome : null,
                                    'conta' => $decoded->data->conta,
                                    'qntidade' => isset($value->quantidade) ? $value->quantidade : 1,
                                    'itemId' => isset($value->itemId) ? $value->itemId : null,
                                ];
                                $row =cadastronormal($this->itemfacturaModel, $itemFactura, $this->db, $this->auditoriaModel);
                                if(!$row){
                                    return $this->respond($row, 501);
                                }
                            }


                        } else {
                            deletarnormal($factura, $this->db, $this->facturaModel, $this->auditoriaModel);
                            deletarnormal($agenda, $this->db, $this->agendaModel, $this->auditoriaModel);
                        }
                    }
                }
            } catch (\Exception $e) {
                $output = [
                    'message' => 'Access denied ',
                    'status' => 401,
                    'error' => true,
                    'type' => $e->getMessage()
                ];

                return $this->respond($output, 401);
            }
        }
    }

    public function webRequest(int $user, string $option)
    {
        switch ($option) {
            case 'getCars':
                # code...
                $response = $this->viaturaModel->where('proprietario', $user)->paginate();
                break;
        }
        return $response;
    }

    private function carPorfile(int $id, $db, $user)
    {
        $data = $db->query("SELECT viaturas.*, marcas.nome AS marca, modelos.nome AS modelo, ano_fabricos.nome AS ano_nome FROM `viaturas` INNER JOIN ano_fabricos ON viaturas.ano=ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo=modelos.id INNER JOIN marcas ON modelos.marca = marcas.id WHERE viaturas.id = $id AND viaturas.proprietario = $user");
        $response = $data->getRow(0);
        return $response;
    }

    private function prestadorServices(int $prestador, $model)
    {
        $response = $model->where('prestador', $prestador)->paginate();
        return $response;
    }

    private function userPorfile($user, $db, $proprietario)
    {
        $data = $db->query("SELECT utilizadors.id, utilizadors.email, utilizadors.estado, utilizadors.proprietario proprietarioID, utilizadors.foto, utilizadors.telefone, utilizadors.username, proprietarios.nome profilename, utilizadors.nif FROM utilizadors INNER JOIN proprietarios ON utilizadors.proprietario=proprietarios.id INNER JOIN contas ON contas.id=proprietarios.conta WHERE utilizadors.id = '$user' AND proprietarios.id = $proprietario");
        $response = $data->getRow(0);
        return $response;
    }

    private function changeUserProfile($user, $proprietario, $data, $model, $foto = null)
    {
        helper('funcao');
        //return returnVoid($data, 501, 'Funcionalidade incompleta!');
        //return var_dump($data['password']);
        if (isset($data['old_password']) && $data['old_password'] != '') {
            $passwordData = [
                'email' => $data['email'],
                'password' => $data['old_password'],
                'new_password' => $data['new_password'],
                'criadopor' => $data['criadopor']
            ];
            $newPass = $this->newPassword($passwordData, $this->db, $model, $this->auditoriaModel);
            if ($newPass['code'] == 400) {
                return $newPass;
            }
        }

        $proprietarioData = [
            'id' => $proprietario,
            'nif' =>isset($data['nif']) ? $data['nif'] : '',
            'nome' => $data['profilename'],
            'criadopor' => $data['criadopor']
        ];

        $utilizadorData = [
            'id' => $user,
            'telefone' => $data['telefone'],
            'nome' => $data['profilename'],
            'nif' => isset($data['nif']) ? $data['nif'] : '',
            'criadopor' => $data['criadopor']
        ];

        $contaData = [
            'id' => $data['conta'],
            'nif' => isset($data['nif']) ? $data['nif'] : '',
            'nome' => $data['profilename'],
            'criadopor' => $data['criadopor']
        ];

        updatecomumafoto($model, $utilizadorData, $this->db, $this->auditoriaModel, 'Utilizador', 'utilizadors', $foto, 'foto');
        updatenormal($this->proprietarioModel, $proprietarioData, $this->auditoriaModel);
        updatenormal($this->contaModel, $contaData, $this->auditoriaModel);

        return $this->userPorfile($user, $this->db, $proprietario);
    }

    private function searcheCar($q, $db, $user)
    {
        $data = $db->query("SELECT viaturas.*, marcas.nome AS marca, modelos.nome AS modelo, ano_fabricos.nome AS ano FROM `viaturas` INNER JOIN ano_fabricos ON viaturas.ano=ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo=modelos.id INNER JOIN marcas ON modelos.marca = marcas.id WHERE (modelos.nome LIKE '$q%' OR marcas.nome LIKE '$q%' OR ano_fabricos.nome LIKE '$q%' OR matricula LIKE '$q%') AND proprietario = $user");
        $response = $data->getResult();
        return $response;
    }

    private function showUserCar($db, $user, $model)
    {
        $data = $db->query("SELECT viaturas.*, marcas.nome AS marca, modelos.id AS modelo_id, modelos.nome AS modelo, ano_fabricos.nome AS ano, ano_fabricos.foto AS imagem FROM `viaturas` INNER JOIN ano_fabricos ON viaturas.ano=ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo=modelos.id INNER JOIN marcas ON modelos.marca = marcas.id WHERE proprietario = $user");
        $response = $data->getResult();

        $row = array();
        foreach ($response as $value) {

            $id = $value->id;
            $proprietario = $value->proprietario;
            $gestVia = $db->query("SELECT * FROM `gestao_viaturas` INNER JOIN viaturas ON gestao_viaturas.viatura = viaturas.id WHERE viaturas.id = $id AND viaturas.proprietario = $proprietario ORDER BY gestao_viaturas.id DESC LIMIT 1")->getRow(0);
            $caracteristica = $db->query("SELECT '' AS dias, '' AS `data`, '' AS pecas, caracteristicas.item AS titulo, caracteristicas.descricao mensagem FROM `caracteristicas` WHERE referencia = $value->modelo_id")->getResult();
            $result = [
                "id" => $value->id,
                "matricula" => $value->matricula,
                "created_at" => $value->created_at,
                "updated_at" => $value->updated_at,
                "deleted_at" => $value->deleted_at,
                "proprietario" => $value->proprietario,
                "ano" => $value->ano,
                "descricao" => $value->descricao,
                "imagem" => $value->imagem,
                "marca" => $value->marca,
                "modelo" => $value->modelo,

                // 'km_actual' => $gestVia->km_actual ?? 0,
                // 'km_diaria_dias_semana' => $gestVia->km_diaria_dias_semana ?? 0,
                // 'km_diaria_final_semana' => $gestVia->km_diaria_final_semana ?? 0,
                // 'data_ultima_revisao' => $gestVia->data_ultima_revisao ?? 0,
                // 'km_na_ultima_revisao' => $gestVia->km_na_ultima_revisao ?? 0,
                // 'periodo_de_revisao' => $gestVia->periodo_de_revisao ?? 0,


                'agenda' => $db->query("SELECT agendas.*, viaturas.matricula, viaturas.imagem imagemViatura, modelos.nome modeloViatura, marcas.nome marcaViatura, ano_fabricos.nome anoFabrico, prestadors.nome nomePrestador, prestadors.telefone telefonePrestador, prestadors.email emailPrestador FROM agendas INNER JOIN viaturas ON agendas.viatura = viaturas.id INNER JOIN ano_fabricos ON viaturas.ano=ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo = modelos.id INNER JOIN marcas ON modelos.marca = marcas.id LEFT JOIN prestadors ON agendas.prestador = prestadors.id WHERE agendas.estado <> 1 AND viaturas.id = $id AND viaturas.proprietario = $proprietario ORDER BY agendas.inicio ASC")->getResult(),
                'gestao' =>  [
                    "id" => $gestVia->id ?? '0',
                    "viatura" => $gestVia->viatura ?? '0',
                    "km_actual" => $gestVia->km_actual ?? '0',
                    "periodo_de_revisao" => $gestVia->periodo_de_revisao ?? '0',
                    "km_diaria_dias_semana" => $gestVia->km_diaria_dias_semana ?? '0',
                    "km_diaria_final_semana" => $gestVia->km_diaria_final_semana ?? '0',
                    "data_ultima_revisao" => $gestVia->data_ultima_revisao ?? '0',
                    "km_na_ultima_revisao" => $gestVia->km_na_ultima_revisao ?? '0',
                    "tipo_oleo" => $gestVia->tipo_oleo ?? '0',
                    "pecas_trocadas" => $gestVia->pecas_trocadas ?? '0',
                    "created_at" => $gestVia->created_at ?? '0',
                    "updated_at" => $gestVia->updated_at ?? '0',
                    "deleted_at" => $gestVia->deleted_at ?? '0',
                    "matricula" => $gestVia->matricula ?? '0',
                    "proprietario" => $gestVia->proprietario ?? '0',
                    "ano" => $gestVia->ano ?? '0',
                    "descricao" => $gestVia->descricao ?? '0',
                    "imagem" => $gestVia->imagem ?? '0',
                ],
                'previsao' => [
            // Tipo de olho apartir da infirmação de modelo.
            // Mudança de Velas .
            // Mudança de dos cauços* de travão.
            // Mudança de pneus.
                    nextMudancaVelas($gestVia->km_actual ?? 0, $gestVia->km_diaria_dias_semana ?? 2, $gestVia->km_diaria_final_semana ?? 5, $gestVia->data_ultima_revisao ?? date('Y-m-d'), $gestVia->km_na_ultima_revisao ?? 0),
                    nextMudancaPneus($gestVia->km_actual ?? 0, $gestVia->km_diaria_dias_semana ?? 2, $gestVia->km_diaria_final_semana ?? 5, $gestVia->data_ultima_revisao ?? date('Y-m-d'), $gestVia->km_na_ultima_revisao ?? 0),
                    nextManutencao($gestVia->km_actual ?? 0, $gestVia->km_diaria_dias_semana ?? 2, $gestVia->km_diaria_final_semana ?? 5, $gestVia->data_ultima_revisao ?? date('Y-m-d'), $gestVia->km_na_ultima_revisao ?? 0, $gestVia->periodo_de_revisao ?? 5000),
                    ...$caracteristica
                ]

            ];

            array_push($row, $result);
        }

        return $row;
    }

    private function newAgendamento($model, $data, $db, $auditoria, $isEmergencia = false)
    {
        helper('funcao');

        $factura = null;
        $agendaData = [
            'inicio' => $data['inicio'],
            'descricao' => $data['descricao'],
            'viatura' => $data['viatura'],
            'prestador' => $data['prestador'],
            'is_domicilio' => $data['is_domicilio'],
            'servico_entrega' => $data['servico_entrega'],
            'categoria' => $data['categoria'],
            'criadopor' => $data['criadopor'],
            'address' => isset($data['address']) ? $data['address'] : '',
            'gps' => isset($data['gps']) ? $data['gps'] : '',
            'table' => $data['table'],
            /*  'estado' => 0,
            'activo' => 1, */
        ];

        $agenda = cadastronormal($model, $agendaData, $db, $auditoria);

        if ($agenda['code'] == 200) {
            $facturaData = [
                'agenda' => $agenda['id'],
                'origem' => $data['categoria'],
                'proprietario' => $data['proprietario'],
                'criadopor' => $data['criadopor'],
                'datafactura' => date('Y-m-d'),
                'conta' => $data['conta'],
                'table' => 'factura',
            ];

            $factura = cadastronormal($this->facturaModel, $facturaData, $this->db, $auditoria);

            $this->db->query("UPDATE `agendas` SET `factura` = " . $factura['id'] . " WHERE `id` = " . $agenda['id']);

            if ($factura['code'] == 200) {
                if ($data['servico_entrega'] == 1) {
                    $itemRow = $this->db->query("SELECT * FROM `servicos` WHERE prestador = 0")->getRow(0);

                    $itemData = [
                        'factura' => $factura['id'],
                        'valor' => isset($data['preco_distancia']) ? $data['preco_distancia'] : $itemRow->valor,
                        'criadopor' => $data['criadopor'],
                        'nome' => $itemRow->nome,
                        'conta' => $data['conta'],
                        'qntidade' => 1,
                        'table' => 'itemfactura',
                    ];

                    cadastronormal($this->itemfacturaModel, $itemData, $db, $auditoria);
                }/*  else {
                    deletarnormal($factura, $db, $this->facturaModel, $auditoria);
                    deletarnormal($agenda, $db, $this->agendaModel, $auditoria);
                } */
            } else {
                deletarnormal($factura, $db, $this->facturaModel, $auditoria);
                deletarnormal($agenda, $db, $this->agendaModel, $auditoria);
            }
        }

        return $agenda;
    }

    private function addAgendaImagem($model, $data)
    {
        $foto = $this->request->getFile('imagem');
        return cadastrocomumafoto($model, $data, $this->db, $this->auditoriaModel, $foto, 'path');
    }

    private function addAgendamentoItem($model, $data, $servico, $agenda)
    {
        $itemRow = $this->db->query("SELECT * FROM `servicos` WHERE id = $servico")->getRow(0);

        $factura = $this->db->query("SELECT * FROM `facturas` WHERE agenda = $agenda")->getRow(0);

        $itemData = [
            'factura' => $factura->id,
            'itemId' => $servico,
            'valor' => isset($data['preco']) ? $data['preco'] : $itemRow->valor,
            'criadopor' => $data['criadopor'],
            'nome' => $itemRow->nome,
            'conta' => $data['conta'],
            'qntidade' => 1,
            'table' => 'itemfactura',
        ];

        if ($itemRow->id == 2) {
            $itemData = [
                'factura' => $factura->id,
                'itemId' => $servico,
                'valor' => isset($data['preco']) ? $data['preco'] : $itemRow->valor,
                'criadopor' => $data['criadopor'],
                'nome' => $itemRow->nome,
                'conta' => $data['conta'],
                'qntidade' => 1,
                'table' => 'itemfactura',
                'distancia' => $data['distancia'],
                'gps' => $data['gps'],
            ];
        }

        return cadastronormal($model, $itemData, $this->db, $this->auditoriaModel);
    }

    private function perfil($id)
    {

        $data = [
            'data' => $this->model->where('id', $id)->paginate()
        ];

        return $this->respond($data);
    }

    private function adicionar()
    {
        $secret_key = $this->protect->privateKey();

        $token = null;

        $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$authHeader) {
            return null;
        }

        $arr = explode(" ", $authHeader);

        $token = $arr[1];

        if ($token) {
            try {
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));
                // return $this->respond($decoded);
                // Access is granted. Add code of the operation here
                if ($decoded) {
                    $data = json_decode(file_get_contents("php://input"));
                    $data = [
                        'nome' => isset($data->nome) ? $data->nome : null,
                        'naruteza_instituicao' => isset($data->naruteza_instituicao) ? $data->naruteza_instituicao : null,
                        'n_ordem' => isset($data->n_ordem) ? $data->n_ordem : null,
                        'data_criacao' => isset($data->data_criacao) ? $data->data_criacao : null,
                        'provincia' => isset($data->provincia) ? $data->provincia : null,
                        'distrito' => isset($data->distrito) ? $data->distrito : null,
                        'bairro' => isset($data->bairro) ? $data->bairro : null,
                        'rua' => isset($data->rua) ? $data->rua : null,
                        'criadopor' => isset($data->criadopor) ? $data->criadopor : null
                    ];

                    helper('funcao');
                    $data = cadastronormal($this->model, $data, $this->db, $this->auditoriaModel, 'Nova Escola');
                    return $this->respond($data);
                }
            } catch (\Exception $e) {
                $output = [
                    'message' => 'Access denied',
                    'code' => 401,
                    'error' => true,
                    'type' => $e->getMessage()
                ];

                return $this->respond($output, 401);
            }
        }
    }

    private function editar($id)
    {
        $secret_key = $this->protect->privateKey();

        $token = null;

        $authHeader = $this->request->getServer('HTTP_AUTHORIZATION');

        if (!$authHeader) {
            return null;
        }

        $arr = explode(" ", $authHeader);

        $token = $arr[1];

        if ($token) {
            try {
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));
                // return $this->respond($decoded);
                // Access is granted. Add code of the operation here
                if ($decoded) {
                    $data = json_decode(file_get_contents("php://input"));
                    $data = [
                        'id' => $id,
                        'nome' => isset($data->nome) ? $data->nome : null,
                        'naruteza_instituicao' => isset($data->naruteza_instituicao) ? $data->naruteza_instituicao : null,
                        'n_ordem' => isset($data->n_ordem) ? $data->n_ordem : null,
                        'data_criacao' => isset($data->data_criacao) ? $data->data_criacao : null,
                        'provincia' => isset($data->provincia) ? $data->provincia : null,
                        'distrito' => isset($data->distrito) ? $data->distrito : null,
                        'bairro' => isset($data->bairro) ? $data->bairro : null,
                        'rua' => isset($data->rua) ? $data->rua : null,
                        'criadopor' => isset($data->criadopor) ? $data->criadopor : null
                    ];

                    helper('funcao');
                    $data = updatenormal($this->model, $data, $this->auditoriaModel, 'Escola');
                    return $this->respond($data);
                }
            } catch (\Exception $e) {
                $output = [
                    'message' => 'Access denied',
                    'code' => 401,
                    'error' => true,
                    'type' => $e->getMessage()
                ];

                return $this->respond($output, 401);
            }
        }
    }

    private function getProducts()
    {
        return  $this->db->query("SELECT produtos.*, ano_fabricos.nome ano, modelos.nome modelo, marcas.nome marca, lojas.nome as loja FROM `produtos` INNER JOIN lojas ON produtos.loja = lojas.id INNER JOIN ano_fabricos ON produtos.ano = ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo = modelos.id INNER JOIN marcas ON modelos.marca = marcas.id")->getResult();
    }

    private function addCarrinho($model, $data)
    {
        helper('funcao');

        $cliente = $data['cliente'];
        $procuto = $data['produto'];
        if ($this->db->query("SELECT COUNT(*) total FROM $model->table WHERE cliente = $cliente AND produto = $procuto")->getRow(0)->total == 1) {
            $item = $model->where([
                'produto' => $procuto,
                'cliente' => $cliente
            ])->first();

            $data = [
                'id' => $item->id,
                'quantidade' => ((int) $item->id + (int) $item->id),
                'total' => ((int) $item->total + (int) $item->id)
            ];

            $resposta = updatenormal($model, $data, $this->auditoriaModel);
        } else {
            $item = $this->produtoModel->where('id', $procuto)->first();
            $data = [
                'cliente' => $cliente,
                'token' => null,
                'produto' => $item->id,
                'quantidade' => 1,
                'preco' => $item->preco,
                'total' => $item->preco
            ];

            $resposta = cadastronormal($model, $data, $this->db, $this->auditoriaModel);
        }

        return $resposta;
    }

    private function remCarrinho($model, $data)
    {
        helper('funcao');

        $cliente = $data['cliente'];
        $procuto = $data['produto'];
        if ($this->db->query("SELECT COUNT(*) total FROM $model->table WHERE (cliente = $cliente AND produto = $procuto) AND quantidade > 0")->getRow(0)->total == 1) {
            $item = $model->where([
                'produto' => $procuto,
                'cliente' => $cliente
            ])->first();

            $data = [
                'id' => $item->id,
                'quantidade' => ((int) $item->id - (int) $item->id),
                'total' => ((int) $item->total - (int) $item->id)
            ];

            $resposta = updatenormal($model, $data, $this->auditoriaModel);
        } else {
            deletarnormal($data, $this->db, $model, $this->auditoriaModel);
            $resposta = returnVoid($data, 401, "Item não encontrado!");
        }

        return $resposta;
    }

    private function limparCarrinho($model, $data)
    {
        helper('funcao');

        $cliente = $data['cliente'];

        $this->db->query("DELETE FROM $model->table WHERE cliente = $cliente");

        return;
    }

    private function newPassword($data, $db, $model, $auditoria)
    {
        helper('funcao');
        if (!isset($data['email']))
            return returnVoid($data, 404, "email não encontrado!");

        $email = $data['email'];
        $user = $db->query("SELECT * FROM utilizadors WHERE email = '$email'")->getRow(0);
        $newPassWord = password_hash($data['new_password'], PASSWORD_BCRYPT);

        if (!is_null($user)) {
            if (password_verify($data['password'], $user->password)) {
                $data2 = [
                    'id' => $user->id,
                    'reset_token' => md5($newPassWord . date('d-m-Y') . $data['email']),
                    'password' => $newPassWord,
                    'criadopor' => $data['criadopor'],
                ];
                return updatenormal($model, $data2, $auditoria);
            }
        }
        return returnVoid($data, 400);
    }

    private function gestaoViaturaPerfil($id, $db, $proprietario)
    {
        helper('funcao');
        return [
            'agenda' => $db->query("SELECT agendas.*, viaturas.matricula, viaturas.imagem imagemViatura, modelos.nome modeloViatura, marcas.nome marcaViatura, ano_fabricos.nome anoFabrico, prestadors.nome nomePrestador, prestadors.telefone telefonePrestador, prestadors.email emailPrestador FROM agendas INNER JOIN viaturas ON agendas.viatura = viaturas.id INNER JOIN ano_fabricos ON viaturas.ano=ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo = modelos.id INNER JOIN marcas ON modelos.marca = marcas.id LEFT JOIN prestadors ON agendas.prestador = prestadors.id WHERE agendas.estado <> 1 AND viaturas.id = $id AND viaturas.proprietario = $proprietario ORDER BY agendas.inicio ASC")->getResult(),
            'gestao' => $gestVia = $db->query("SELECT * FROM `gestao_viaturas` INNER JOIN viaturas ON gestao_viaturas.viatura = viaturas.id WHERE viaturas.id = $id AND viaturas.proprietario = $proprietario ORDER BY gestao_viaturas.id DESC")->getRow(0),
            'previsao' => [
                nextManutencao($gestVia->km_actual, $gestVia->km_diaria_dias_semana, $gestVia->km_diaria_final_semana, $gestVia->data_ultima_revisao, $gestVia->km_na_ultima_revisao, $gestVia->periodo_de_revisao),
            ]  /* Aqui eu tenho de preecher com as outras informaçoes que podemos encontrar em uma viatura e ainda nao estão a ser cadastradas. */
        ];
    }

    private function getAgendaNotifications($db, $proprietario)
    {
        helper('funcao');
        $count = $db->query("SELECT agendas.*, viaturas.matricula, viaturas.imagem imagemViatura, modelos.nome modeloViatura, marcas.nome marcaViatura, ano_fabricos.nome anoFabrico, prestadors.nome nomePrestador, prestadors.telefone telefonePrestador, prestadors.email emailPrestador FROM agendas INNER JOIN viaturas ON agendas.viatura = viaturas.id INNER JOIN ano_fabricos ON viaturas.ano=ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo = modelos.id INNER JOIN marcas ON modelos.marca = marcas.id LEFT JOIN prestadors ON agendas.prestador = prestadors.id WHERE viaturas.proprietario = $proprietario AND activo = 1 AND estado <> 1 ORDER BY agendas.inicio ASC")->getResult();

        return [
            'agenda' => $db->query("SELECT agendas.*, viaturas.matricula, viaturas.imagem imagemViatura, modelos.nome modeloViatura, marcas.nome marcaViatura, ano_fabricos.nome anoFabrico, prestadors.nome nomePrestador, prestadors.telefone telefonePrestador, prestadors.email emailPrestador FROM agendas INNER JOIN viaturas ON agendas.viatura = viaturas.id INNER JOIN ano_fabricos ON viaturas.ano=ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo = modelos.id INNER JOIN marcas ON modelos.marca = marcas.id LEFT JOIN prestadors ON agendas.prestador = prestadors.id WHERE viaturas.proprietario = $proprietario AND activo = 1 ORDER BY agendas.inicio ASC")->getResult(),
            'notifications' => $db->query("SELECT agendas.*, viaturas.matricula, viaturas.imagem imagemViatura, modelos.nome modeloViatura, marcas.nome marcaViatura, ano_fabricos.nome anoFabrico, prestadors.nome nomePrestador, prestadors.telefone telefonePrestador, prestadors.email emailPrestador FROM agendas INNER JOIN viaturas ON agendas.viatura = viaturas.id INNER JOIN ano_fabricos ON viaturas.ano=ano_fabricos.id INNER JOIN modelos ON ano_fabricos.modelo = modelos.id INNER JOIN marcas ON modelos.marca = marcas.id LEFT JOIN prestadors ON agendas.prestador = prestadors.id WHERE viaturas.proprietario = $proprietario AND activo = 1 AND agendas.estado <> 1 ORDER BY agendas.inicio ASC")->getResult(),
            'total' => count($count) /* Aqui eu tenho de preecher com as outras informaçoes que podemos encontrar em uma viatura e ainda nao estão a ser cadastradas. */
        ];
    }




    /**
     * Lista de todos os prestadores de servili e seus serviços
     * os pretadores têm categoria:
     *  1 -  Lavagem, 
     *  2 - Manutenção,
     *  3 - Hibrido
     * 
     * Cada serciço pode ser:
     *  1 - Lavagem
     *  2 - Manutenção
     */
    private function getPrestadores($db, $model)
    {
        helper('funcao');
        /* Doravante a categoria 1 será relacionada a lavangem de carros, categoria 2 será para manutencão e repareçao */
        $response = $db->query("SELECT $model->table.* FROM $model->table INNER JOIN servicos ON $model->table.id = servicos.prestador WHERE $model->table.id != 2 GROUP BY $model->table.id")->getResult();

        $row = array();
        foreach ($response as $value) {

            $id = $value->id;

            $result = [
                'id' =>  $id,
                'nome' =>  $value->nome,
                'nif' =>  $value->nif,
                'email' =>  $value->email,
                'telefone' =>  $value->telefone,
                'endereco' =>  $value->endereco,
                'criadopor' =>  $value->criadopor,
                'foto' => $value->foto,
                'site' =>  $value->site,
                'androidlink' => $value->androidlink,
                'ioslink' => $value->ioslink,
                'created_at' =>  $value->created_at,
                'updated_at' =>  $value->updated_at,
                'deleted_at' => $value->deleted_at,
                'gps_latitude' => $value->gps_latitude,
                'gps_longitude' =>  $value->gps_longitude,
                'w3w' => $value->w3w,
                'country' =>  $value->country,
                'provincia' =>  $value->provincia,
                'municipio' => $value->municipio,
                'distrito' => $value->distrito,
                'comuna' => $value->comuna,
                'bairro' => $value->bairro,
                'n_casa' => $value->n_casa,
                'tipo' => $value->tipo,
                'img1' => $value->img1,
                'img2' => $value->img2,
                'img3' => $value->img3,
                'img4' => $value->img4,
                'img5' => $value->img5,
                'services' => $db->query("SELECT servicos.* FROM servicos WHERE prestador = $id")->getResult(),
            ];

            array_push($row, $result);
        }

        return $row;

        return $response;
    }

    private function chooseModel(string $table)
    {
        switch ($table) {
            case 'agenda':
                return $this->agendaModel;
            case 'agendaimagem':
                return $this->agendaimagemModel;
            case 'anofabrico':
                return $this->anofabricoModel;
            case 'auditoria':
                return $this->auditoriaModel;
            case 'contacto':
                return $this->contactoModel;
            case 'conta':
                return $this->contaModel;
            case 'endereco':
                return $this->enderecoModel;
            case 'emergencia':
                return $this->emergenciaModel;
            case 'factura':
                return $this->facturaModel;
            case 'gestaoviatura':
                return $this->gestaoviaturaModel;
            case 'itemAgenda':
                return $this->itemfacturaModel;
            case 'marca':
                return $this->marcaModel;
            case 'modelo':
                return $this->modeloModel;
            case 'olegestaoviatura':
                return $this->olegestaoviaturaModel;
            case 'prestador':
                return $this->prestadorModel;
            case 'proprietario':
                return $this->proprietarioModel;
            case 'seguros':
                return $this->seguroModel;
            case 'sinistro':
                return $this->sinistroModel;
            case 'servico':
                return $this->servicoModel;
            case 'tipoconta':
                return $this->tipocontaModel;
            case 'tipoitem':
                return $this->tipoitemModel;
            case 'utilizacao':
                return $this->utilizacaoModel;
            case 'utilizador':
                return $this->utilizadorModel;
            case 'viatura':
                return $this->viaturaModel;
            case 'produtos':
                return $this->produtoModel;
            case 'caracteristica':
                return $this->caracteristicaModel;
                //Eu ainda nao tratei dessa parte do app
            case 'loja':
                return $this->lojaModel;
            case 'avaliacao':
                return $this->avaliacaoModel;
            case 'categoria':
                return $this->categoriaModel;
            case 'produto':
                return $this->produtoModel;
            case 'carrinho':
                return $this->carrinhoModel;
            default:
                return false;
        }
    }
}
