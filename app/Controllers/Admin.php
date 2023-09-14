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
use Firebase\JWT\Key;
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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));
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

    public function gerarFacturaapi()
    {
        $factura = $this->request->getPost('factura');
        $proprietario = $this->request->getPost('proprietario');

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1) {
                        $db = Database::connect();
                        helper('funcao');
                        $pagamento = $db->query("SELECT * FROM `facturas` WHERE id = $factura")->getRow(0);

                        if ($pagamento->hash_factura != null) {
                            $output = [
                                'message' => 'Este pagamento já possui uma factura!',
                                'status' => 401,
                                'error' => true,
                            ];
                            return $this->respond($output, 401);
                        }

                        $clienteData = $db->query("SELECT proprietarios.nome, proprietarios.nif as bi, contas.nif, utilizadors.telefone, utilizadors.email  FROM `proprietarios` INNER JOIN `contas` ON proprietarios.conta = contas.id INNER JOIN utilizadors ON proprietarios.id = utilizadors.proprietario WHERE proprietarios.id = $proprietario")->getRow(0);
                        // echo $firmaData->api;
                        #Aqui ficam os dados que depois serão enviados como response

                        $cliente = [
                            "name" => $clienteData->nome,
                            "fiscal_id" => ($clienteData->bi != null) ? $clienteData->bi : '',
                            "email" => $clienteData->email,
                            "address" => 'Angola, Luanda', //Tendo de adicionar compos para endereço do prestador de serviço.
                            "city" => "Luanda",
                            "country" => "Angola",
                            "phone" => $clienteData->telefone,
                            "fax" => "",
                            "mobile" => "",
                            "postal_code" => "",
                            "short_name" => "",
                            "sigla" => "",
                            "website" => "",
                        ];
                        if ($clienteData->bi != null) {
                            $factura = [
                                "date" => date('Y-m-d'),
                                "due_date" => "",
                                "observations" => "",
                                "reference" => "",
                                "retencao" => "",
                                "type" => "FT",
                                "vref" => "",
                                "client" => $cliente,
                                "items" => $db->query("SELECT itemfacturas.nome AS `name`, itemfacturas.valor AS unit_price, itemfacturas.qntidade AS quantity, 'servico' AS type, '0' AS discount, 'Meu Ruca' AS description FROM itemfacturas WHERE factura = $pagamento->id")->getResult(),
                            ];
                        } else {
                            $factura = [
                                "date" => date('Y-m-d'),
                                "due_date" => "",
                                "observations" => "",
                                "reference" => "",
                                "retencao" => "",
                                "type" => "FT",
                                "vref" => "",
                                "items" => $db->query("SELECT itemfacturas.nome AS `name`, itemfacturas.valor AS unit_price, itemfacturas.qntidade AS quantity, 'servico' AS type, '0' AS discount, 'Meu Ruca' AS description FROM itemfacturas WHERE factura = $pagamento->id")->getResult(),
                            ];
                        }

                        $data = [
                                "invoice" =>  $factura
                            ];

                         //return $this->respond($data);

                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.zcomercial.com/v1/invoice/create',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($data, JSON_PRETTY_PRINT, JSON_UNESCAPED_UNICODE),
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: ' . '9a2d9d31610ea03b16260515e479ff73ce2f01d0',
                                'Content-Type: application/json',
                            ),
                        ));

                        $response = json_decode(curl_exec($curl));

                        // Check HTTP status code
                        if (!curl_errno($curl)) {

                            switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
                                case 200: # OK	Everything worked as expected.
                                    $db->query("UPDATE `facturas` SET `numero`= '" . $response->invoice->sequence_number . "', `datafactura`= '" . $response->invoice->date . "', `estado`= 1, `final`= 1, `hash_factura`= '" .$response->invoice->id ."' WHERE id = $pagamento->id;");
                                    $db->query("UPDATE `agendas` SET `estado`= 1 WHERE factura = $pagamento->id;");
                                    return $this->respond($response, 200);
                                    break;
                                case 400: # Bad Request	The request was unacceptable, often due to missing a required parameter.
                                    return $this->respond($response);
                                    break;
                                case 401: # Unauthorized	No valid API key provided.
                                    return $this->respond($response);
                                    break;
                                case 402: # Request Failed	The parameters were valid but the request failed.
                                    return $this->respond($response);
                                    break;
                                case 409: # Conflict	The request conflicts with another request (perhaps due to using the same idempotent key).
                                    return $this->respond($response);
                                    break;
                                case 429: # Too Many Requests	Too many requests hit the API too quickly. We recommend an exponential backoff of your requests.
                                    return $this->respond($response);
                                    break;
                                case 500:
                                case 502:
                                case 503:
                                case 504:                    # Server Errors	Something went wrong on example's end. (These are rare.)
                                    return $this->respond($response);
                                    break;
                                case 404: # ERROR
                                    return $this->respond($response);
                                    break;
                                default:
                                    echo 'Unexpected HTTP code: ', $http_code, "\n";
                                    return $this->respond($response);
                            }
                        }

                        curl_close($curl);

                        return $this->respond($response);
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


    public function sendSMS()
    {
        $factura = $this->request->getPost('factura');
        $proprietario = $this->request->getPost('proprietario');

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
                $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1) {
                        $db = Database::connect();
                        helper('funcao');
                        $pagamento = $db->query("SELECT * FROM `facturas` WHERE id = $factura")->getRow(0);

                        if ($pagamento->hash_factura == null) {
                            $output = [
                                'message' => 'Este pagamento ainda não possui uma factura!',
                                'status' => 401,
                                'error' => true,
                            ];
                            return $this->respond($output, 401);
                        }

                        $clienteData = $db->query("SELECT proprietarios.nome, proprietarios.nif as bi, contas.nif, utilizadors.telefone, utilizadors.email  FROM `proprietarios` INNER JOIN `contas` ON proprietarios.conta = contas.id INNER JOIN utilizadors ON proprietarios.id = utilizadors.proprietario WHERE proprietarios.id = $proprietario")->getRow(0);
                        $emissorMessage = "Saudações, Sr/Sra " . $clienteData->nome . ", Gostariamos de noficar sobre o estado de pagamento da factura " . $pagamento->numero;

                        // echo $firmaData->api;
                        //return $this->respond($data);

                        $curl = curl_init();

                        curl_setopt_array($curl, [
                            CURLOPT_URL => "https://api.wesender.co.ao/envio/apikey",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => "{\n\t\"ApiKey\" : \"f450dec675494dc48e87e59ec29afcb77a4796587272496fb81d22020e91044a\",\n\"Destino\" : [\"". $clienteData->telefone ."\"],\n\"Mensagem\" : \"$emissorMessage\",\n\"CEspeciais\" : \"false\"\n}",
                            CURLOPT_HTTPHEADER => [
                                "Content-Type: application/json"
                            ],
                        ]);

                        $response = json_decode(curl_exec($curl));

                        // Check HTTP status code
                        if (!curl_errno($curl)) {

                            switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
                                case 200: # OK	Everything worked as expected.
                                    return $this->respond($response);
                                    break;
                                case 400: # Bad Request	The request was unacceptable, often due to missing a required parameter.
                                    return $this->respond($response);
                                    break;
                                case 401: # Unauthorized	No valid API key provided.
                                    return $this->respond($response);
                                    break;
                                case 402: # Request Failed	The parameters were valid but the request failed.
                                    return $this->respond($response);
                                    break;
                                case 409: # Conflict	The request conflicts with another request (perhaps due to using the same idempotent key).
                                    return $this->respond($response);
                                    break;
                                case 429: # Too Many Requests	Too many requests hit the API too quickly. We recommend an exponential backoff of your requests.
                                    return $this->respond($response);
                                    break;
                                case 500:
                                case 502:
                                case 503:
                                case 504:                    # Server Errors	Something went wrong on example's end. (These are rare.)
                                    return $this->respond($response);
                                    break;
                                case 404: # ERROR
                                    return $this->respond($response);
                                    break;
                                default:
                                    echo 'Unexpected HTTP code: ', $http_code, "\n";
                                    return $this->respond($response);
                            }
                        }
                        curl_close($curl);
                        return $this->respond($response);
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

    public function sendEmail()
    {
        $db = Database::connect();
        $factura = $this->request->getPost('factura');
        $proprietario = $this->request->getPost('proprietario');

        $pagamento = $db->query("SELECT * FROM `facturas` WHERE id = $factura")->getRow(0);

        if ($pagamento->hash_factura == null) {
            $output = [
                'message' => 'Este pagamento ainda nao possuí uma factura!',
                'status' => 401,
                'error' => true,
            ];
            return $this->respond($output, 401);
        }

        $clienteData = $db->query("SELECT proprietarios.nome, proprietarios.nif as bi, contas.nif, utilizadors.telefone, utilizadors.email  FROM `proprietarios` INNER JOIN `contas` ON proprietarios.conta = contas.id INNER JOIN utilizadors ON proprietarios.id = utilizadors.proprietario WHERE proprietarios.id = $proprietario")->getRow(0);

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
                $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1) {
                        helper('funcao');
                        try {
                            $email = \Config\Services::email();

                            $emissorEmail = 'info@meuruca.ao';
                            $emissorName = 'Meu Ruca';
                            $emissorSubject = 'Factura - '. $pagamento->numero;
                            $emissorMessage = "Saudações. <br><br>Sr/Sra " . $clienteData->nome . ", Gostariamos de noficar sobre o estado de pagamento da factura <b>" . $pagamento->numero . "<b>";
                            $receptor = $clienteData->email;
                            // $file = $this->request->getFile('file');

                            // if (($file != null)) {
                            //     $email->attach($file->getExtension(), $file->getBasename());
                            // }
                            $email->setFrom($emissorEmail, $emissorName);
                            $email->setTo($receptor);

                            $email->setSubject($emissorSubject);
                            $email->setMessage($emissorMessage . '<br>De: ' . $emissorEmail);

                            if ($email->send()) {
                                return $this->respond([
                                    'message' => 'Success'
                                ], 200);
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

                        return $this->respond([
                            'code' => 0,
                            'message' => "Utilizador não encontrado!"
                        ], 400);
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

    public function finalizarPagamento()
    {
        $db = Database::connect();
        $factura = $this->request->getPost('factura');
        $proprietario = $this->request->getPost('proprietario');

        $pagamento = $db->query("SELECT * FROM `facturas` WHERE id = $factura")->getRow(0);

        if ($pagamento->hash_factura == null) {
            $output = [
                'message' => 'Este pagamento ainda nao possuí uma factura!',
                'status' => 401,
                'error' => true,
            ];
            return $this->respond($output, 401);
        }

        $clienteData = $db->query("SELECT proprietarios.nome, proprietarios.nif as bi, contas.nif, utilizadors.telefone, utilizadors.email  FROM `proprietarios` INNER JOIN `contas` ON proprietarios.conta = contas.id INNER JOIN utilizadors ON proprietarios.id = utilizadors.proprietario WHERE proprietarios.id = $proprietario")->getRow(0);
        
        $sql = $db->query("UPDATE `facturas` SET `pago`= 1, `estado`= 2 WHERE id = $pagamento->id;");
        $db->query("UPDATE `agendas` SET `estado`= 1 WHERE factura = $pagamento->id;");

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
                $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1 && $sql) {
                        try {
                            $email = \Config\Services::email();

                            $emissorEmail = 'info@meuruca.ao';
                            $emissorName = 'Meu Ruca';
                            $emissorSubject = 'Factura - ' . $pagamento->numero;
                            $emissorMessage = "Saudações. <br><br>Sr/Sra " . $clienteData->nome . ", Gostariamos de noficar que a factura " . $pagamento->numero . ", foi paga com sucesso <br><b>Muito obrigado pele preferência!<b>";
                            $receptor = $clienteData->email;
                            // $file = $this->request->getFile('file');

                            // if (($file != null)) {
                            //     $email->attach($file->getExtension(), $file->getBasename());
                            // }
                            $email->setFrom($emissorEmail, $emissorName);
                            $email->setTo($receptor);

                            $email->setSubject($emissorSubject);
                            $email->setMessage($emissorMessage . '<br>De: ' . $emissorEmail);

                            if ($email->send()) {
                                return $this->respond([
                                    'message' => 'Success'
                                ], 200);
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

    public function finalizarAgendamento()
    {
        $db = Database::connect();
        $agenda = $this->request->getPost('factura');
        $proprietario = $this->request->getPost('proprietario');

        $clienteData = $db->query("SELECT proprietarios.nome, proprietarios.nif as bi, contas.nif, utilizadors.telefone, utilizadors.email  FROM `proprietarios` INNER JOIN `contas` ON proprietarios.conta = contas.id INNER JOIN utilizadors ON proprietarios.id = utilizadors.proprietario WHERE proprietarios.id = $proprietario")->getRow(0);

        $sql = $db->query("UPDATE `agendas` SET `estado`= 3 WHERE factura = $agenda;");

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
                $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1 && $sql) {
                        try {
                            $email = \Config\Services::email();

                            $emissorEmail = 'info@meuruca.ao';
                            $emissorName = 'Meu Ruca';
                            $emissorSubject = 'Serviço finalizado';
                            $emissorMessage = "Saudações. <br><br>Sr/Sra " . $clienteData->nome . ", O serviço está concluido, gostariamos de saber a tua avaliação. <br><b>Muito obrigado pele preferência!<b>";
                            $receptor = $clienteData->email;
                            // $file = $this->request->getFile('file');

                            // if (($file != null)) {
                            //     $email->attach($file->getExtension(), $file->getBasename());
                            // }
                            $email->setFrom($emissorEmail, $emissorName);
                            $email->setTo($receptor);

                            $email->setSubject($emissorSubject);
                            $email->setMessage($emissorMessage . '<br>De: ' . $emissorEmail);

                            if ($email->send()) {
                                return $this->respond([
                                    'message' => 'Success'
                                ], 200);
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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1) {
                        helper('funcao');

                        $foto = $this->request->getFile('foto');
                        $img1 = $this->request->getFile('img1');
                        $img2 = $this->request->getFile('img2');
                        $img3 = $this->request->getFile('img3');
                        $img4 = $this->request->getFile('img4');
                        $img5 = $this->request->getFile('img5');

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

                        $resposta = cadastrocomseisfotos($this->prestadorModel, $data, $this->db, $this->auditoriaModel, 'Novo Prestador', $foto, 'foto', $img1, 'img1', $img2, 'img2' ,$img3, 'img3', $img4, 'img4' , $img5, 'img5');

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

                if ($decoded) {

                    if ($decoded->data->acesso > 1) {
                        helper('funcao');

                        $foto = $this->request->getFile('foto');
                        $img1 = $this->request->getFile('img1');
                        $img2 = $this->request->getFile('img2');
                        $img3 = $this->request->getFile('img3');
                        $img4 = $this->request->getFile('img4');
                        $img5 = $this->request->getFile('img5');

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

                        $resposta = updatecomseisfotos($this->prestadorModel, $data, $decoded->data->id, $this->db, $this->auditoriaModel, 'Prestador', $foto, 'foto', $img1, 'img1', $img2, 'img2', $img3, 'img3',$img4, 'img4', $img5, 'img5');

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
                $decoded =JWT::decode($token, new Key($secret_key, 'HS256'));

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
