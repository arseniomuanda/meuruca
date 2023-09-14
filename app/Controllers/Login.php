<?php

namespace App\Controllers;

use Config\Database;
use \Config\Services;

use CodeIgniter\RESTful\ResourceController;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\AuditoriaModel;
use App\Models\ContaModel;
use App\Models\ProprietarioModel;
use App\Models\UtilizadorModel;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;

class Login extends ResourceController
{
    protected $db;
    protected $utilizadorModel;
    protected $auditoriaModel;
    protected $proprietarioModel;
    protected $contaModel;
    protected $model;
    protected $session;

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
    }

    public function privateKey()
    {
        $privateKey = "<<<EOD
        -----BEGIN RSA PRIVATE KEY-----
        MIICXAIBAAKBgQC8kGa1pSjbSYZVebtTRBLxBz5H4i2p/llLCrEeQhta5kaQu/Rn
        vuER4W8oDH3+3iuIYW4VQAzyqFpwuzjkDI+17t5t0tyazyZ8JXw+KgXTxldMPEL9
        5+qVhgXvwtihXC1c5oGbRlEDvDF6Sa53rcFVsYJ4ehde/zUxo6UvS7UrBQIDAQAB
        AoGAb/MXV46XxCFRxNuB8LyAtmLDgi/xRnTAlMHjSACddwkyKem8//8eZtw9fzxz
        bWZ/1/doQOuHBGYZU8aDzzj59FZ78dyzNFoF91hbvZKkg+6wGyd/LrGVEB+Xre0J
        Nil0GReM2AHDNZUYRv+HYJPIOrB0CRczLQsgFJ8K6aAD6F0CQQDzbpjYdx10qgK1
        cP59UHiHjPZYC0loEsk7s+hUmT3QHerAQJMZWC11Qrn2N+ybwwNblDKv+s5qgMQ5
        5tNoQ9IfAkEAxkyffU6ythpg/H0Ixe1I2rd0GbF05biIzO/i77Det3n4YsJVlDck
        ZkcvY3SK2iRIL4c9yY6hlIhs+K9wXTtGWwJBAO9Dskl48mO7woPR9uD22jDpNSwe
        k90OMepTjzSvlhjbfuPN1IdhqvSJTDychRwn1kIJ7LQZgQ8fVz9OCFZ/6qMCQGOb
        qaGwHmUK6xzpUbbacnYrIM6nLSkXgOAwv7XXCojvY614ILTK3iXiLBOxPu5Eu13k
        eUz9sHyD6vkgZzjtxXECQAkp4Xerf5TGfQXGXhxIX52yH+N2LtujCdkQZjXAsGdm
        B2zNzvrlgRmgBrklMTrMYgm1NPcW+bRLGcwgW2PTvNM=
        -----END RSA PRIVATE KEY-----
        EOD";

        return $privateKey;
    }

    private function validar_login($email)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM utilizadors WHERE email = '$email'")->getRow(0)->total;
        if ($query > 0) {
            $data = $this->db->query("SELECT utilizadors.*, proprietarios.nome porfilename, proprietarios.conta conta, proprietarios.id proprietario FROM utilizadors INNER JOIN proprietarios ON utilizadors.proprietario=proprietarios.id INNER JOIN contas ON contas.id=proprietarios.conta WHERE utilizadors.email = '$email';")->getRow(0);
        } else {
            $data = null;
        }
        return $data;
    }

    private function validar_admin($email)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM utilizadors WHERE email = '$email'")->getRow(0)->total;
        if ($query > 0) {
            $data = $this->db->query("SELECT * FROM utilizadors WHERE email = '$email'")->getRow(0);
        } else {
            $data = null;
        }
        return $data;
    }

    public function index()
    {
        helper('funcao');
        $email      = $this->request->getPost('email');
        $password   = $this->request->getPost('password');

        $user       = $this->validar_login($email);
        if (!is_null($user)) {
            if (password_verify($password, $user->password)) {
                if (!$user->confirmed_email) {
                    $this->askEmailConfirmation($email);
                    return returnVoid([
                        'message' => 'Confirmar Email!'
                    ], 403, 'Email não confirmado');
                }
                $id = $user->id;
                $dataLigin = date("Y-m-d H:i:s");

                $this->auditoriaModel->save([
                    'accao' => 'Login',
                    'processo' => 'Login',
                    'registo' => $user->id,
                    'utilizadors' => $user->id,
                    'dataAcao' => date('Y-m-d'),
                    'dataExpiracao' => date("Y-m-d H:i:s'", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
                ]);

                $secret_key = $this->privateKey();
                $issuer_claim = 'THE_CLAIN';
                $audience_claim = 'THE_AUDIENCE';
                $issuedat_claim = time();
                $notbefore_claim = $issuedat_claim + 0;
                $expire_claim = $issuedat_claim + 3600 * 24;

                $token = [
                    "iss"  => $issuer_claim,
                    "aud"  => $audience_claim,
                    "iat"  => $issuedat_claim,
                    "nbf"  => $notbefore_claim,
                    "exp"  => $expire_claim,
                    "data" => [
                        'acesso'    => 1, // Este acesso vai ser actualizado para vir da base de dados
                        'email'     => $email,
                        'id'        => $user->id,
                        'conta'     => $user->conta,
                        'proprietario' => $user->proprietario,
                        'nif' => $user->nif
                    ]
                ];

                // Gerar Token
                $token = JWT::encode($token, $secret_key, 'HS256');

                $this->db->query("UPDATE `utilizadors` SET `ultimoAcesso`= '$dataLigin',  api_token = '$token' WHERE id = $id");
                $this->auditoriaModel->save([
                    'accao' => 'Reset',
                    'processo' => 'Reset da palavra pass',
                    'registo' => $user->id,
                    'utilizadors' => $user->id,
                    'dataAcao' => date('Y-m-d'),
                    'dataExpiracao' => date("Y-m-d H:i:s'", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
                ]);

                $data = [
                    'message'   => 'Login successfully',
                    'token'     => $token,
                    'expireAt'  => date('Y-m-d H:i:s', $expire_claim),
                    'now'       => date('Y-m-d H:i:s'),
                    'email'     => $email,
                    'id'        => $user->id,
                    'nif'        => $user->nif,
                    'username'  => $user->username,
                    'profilename' => $user->porfilename,
                    'telefone' => $user->telefone,
                    'proprietario' => $user->proprietario,
                    'conta'     => $user->conta,
                    'logado'        => true
                ];

                $this->session->set($data);

                return $this->respond($data, 200);
            } else {
                $data = [
                    'message'   => 'Palavra pass errada!'
                ];

                return $this->respond($data, 401);
            }
        } else {
            $data = [
                'message'   => 'Email não encontrado!',
            ];

            return $this->respond($data, 401);
        }
    }

    public function admin()
    {
        helper('funcao');
        $email      = $this->request->getPost('email');
        $password   = $this->request->getPost('password');

        $user       = $this->validar_admin($email);
        if (!is_null($user)) {
            if (password_verify($password, $user->password) && $user->acesso > 1) {

                $id = $user->id;
                $dataLigin = date("Y-m-d H:i:s");

                $this->auditoriaModel->save([
                    'accao' => 'Login',
                    'processo' => 'Login',
                    'registo' => $user->id,
                    'utilizadors' => $user->id,
                    'dataAcao' => date('Y-m-d'),
                    'dataExpiracao' => date("Y-m-d H:i:s'", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
                ]);

                $secret_key = $this->privateKey();
                $issuer_claim = 'THE_CLAIN';
                $audience_claim = 'THE_AUDIENCE';
                $issuedat_claim = time();
                $notbefore_claim = $issuedat_claim + 0;
                $expire_claim = $issuedat_claim + 3600 * 24;

                $token = [
                    "iss"  => $issuer_claim,
                    "aud"  => $audience_claim,
                    "iat"  => $issuedat_claim,
                    "nbf"  => $notbefore_claim,
                    "exp"  => $expire_claim,
                    "data" => [
                        'acesso'    => $user->acesso, // Este acesso vai ser actualizado para vir da base de dados
                        'email'     => $email,
                        'id'        => $user->id,
                        'conta'     => 1,
                        'proprietario' => 1
                    ]
                ];

                // Gerar Token
                $token = JWT::encode($token, $secret_key, 'HS256');

                $this->db->query("UPDATE `utilizadors` SET `ultimoAcesso`= '$dataLigin',  api_token = '$token' WHERE id = $id");
                $this->auditoriaModel->save([
                    'accao' => 'Reset',
                    'processo' => 'Reset da palavra pass',
                    'registo' => $user->id,
                    'utilizadors' => $user->id,
                    'dataAcao' => date('Y-m-d'),
                    'dataExpiracao' => date("Y-m-d H:i:s'", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
                ]);

                $data = [
                    'message'   => 'Login successfully',
                    'token'     => $token,
                    'expireAt'  => date('Y-m-d H:i:s', $expire_claim),
                    'now'       => date('Y-m-d H:i:s'),
                    'email'     => $email,
                    'id'        => $user->id,
                    'username'  => $user->username,
                    'name' => $user->nome,
                    'telefone' => $user->telefone,
                    'proprietario' => 1,
                    'conta'     => 1,
                    'logado'        => true
                ];

                $this->session->set($data);

                return $this->respond($data, 200);
            } else {
                $data = [
                    'message'   => 'Palavra pass errada!'
                ];

                return $this->respond($data,401);
            }
        } else {
            $data = [
                'message'   => 'Email não encontrado!',
            ];

            return $this->respond($data, 401);
        }
    }

    public function logout()
    {
        $this->session->destroy();
        $id = $this->request->getPost('id');
        $this->db->query("UPDATE `utilizadors` SET `api_token`= '' WHERE id = $id");;
        return $this->respond([
            'code' => 200
        ], 200);
    }

    public function askResetPassword()
    {
        helper('funcao');
        $email      = $this->request->getPost('email');

        $user       = $this->validar_login($email);
        if (!is_null($user)) {

            $newDueDate = date('Y-m-d H:i:s', strtotime('+2 minutes', strtotime(date('Y-m-d H:i:s'))));

            $this->db->query("UPDATE `utilizadors` SET `api_token_date`= '$newDueDate' WHERE email = '$email'");

            return $this->respond([
                'code' => 200,
                'token' => $user->reset_token,
                'tempo' => $user->api_token_date
            ]);
        }
        return $this->respond([
            'code' => 0,
            'message' => "Utilizador não encontrado!"
        ], 400);
    }

    public function askEmailConfirmation($conta)
    {
        helper('funcao');
        $user       = $this->validar_login($conta);
        try {
            $email = \Config\Services::email();

            $emissorEmail = 'system@meuruca.ao';
            $emissorName = 'Meu Ruca';
            $emissorSubject = 'Confirmação de E-mail';

            if (filter_var($conta, FILTER_VALIDATE_EMAIL)) {

                $newDueDate = date('Y-m-d H:i:s', strtotime('+15 minutes', strtotime(date('Y-m-d H:i:s'))));

                $token_before_hash = $conta . $user->id . $newDueDate;
                $token = md5("12345678910" . $token_before_hash);
                $this->db->query("UPDATE `utilizadors` SET `confirm_email_token_data` = '$newDueDate', `confirm_email_token` = '$token' WHERE email = '$conta'");

                $email->setFrom($emissorEmail, $emissorName);
                $email->setTo($conta);

                $email->setSubject($emissorSubject);
                $email->setMessage(base_url() . 'login/confiremail/' . $token);

                if ($email->send()) {
                    return $this->respondNoContent();
                }
                return $this->respond([
                    'message' => $email->printDebugger(['headers'])
                ], 501);
            } else {
                return $this->respond([
                    'message' => 'Email not found!' . " " . $conta
                ], 401);
            }
        } catch (\Throwable $th) {
            return $this->respond(
                [
                    'message' => 'Erro',
                    'error' => $th->getMessage()
                ],
                501
            );
        }

        return $this->respond([
            'code' => 0,
            'message' => "Utilizador não encontrado!"
        ], 400);
    }

    public function confiremail($token)
    {
        helper('funcao');

        if (!isset($token)) {

            return $this->respond(returnVoid($token, 404, "Token não encontrado!"));
        }

        $user = $this->db->query("SELECT * FROM utilizadors WHERE confirm_email_token = '$token'")->getRow(0);

        // return $this->respond($user);


        if (strtotime(date('Y-m-d H:i:s')) > strtotime(date($user->confirm_email_token_data))) {
            $newDueDate = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime(date('Y-m-d H:i:s'))));

            $token_before_hash = $user->email . $user->id . $newDueDate;
            $token = md5("12345678910" . $token_before_hash);
            $this->db->query("UPDATE `utilizadors` SET `confirm_email_token_data` = '$newDueDate', `confirm_email_token` = '$token' WHERE email = '$user->email'");

            return $this->respond(returnVoid([], 404, "Token Expirado!"));
        }

        $data = [
            'email' => $user->email,
            'token' => $user->confirm_email_token,
            'tempo' =>  $user->confirm_email_token_data
        ];

        return view('templates/confirmemail', $data);
    }

    public function setConfirmedEmail($token)
    {
        helper('funcao');

        if (!isset($token)) {
            return $this->respond(returnVoid($token, 404, "Token não encontrado!"));
        }

        $user = $this->db->query("SELECT * FROM utilizadors WHERE confirm_email_token = '$token'")->getRow(0);

        // return $this->respond($user);


        if (strtotime(date('Y-m-d H:i:s')) > strtotime(date($user->confirm_email_token_data))) {
            $newDueDate = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime(date('Y-m-d H:i:s'))));

            $token_before_hash = $user->email . $user->id . $newDueDate;
            $token = md5("12345678910" . $token_before_hash);
            $this->db->query("UPDATE `utilizadors` SET `confirm_email_token_data` = '$newDueDate', `confirm_email_token` = '$token' WHERE email = '$user->email'");

            return $this->respond(returnVoid([], 404, "Token Expirado!"));
        }

        $this->db->query("UPDATE `utilizadors` SET `confirm_email_token_data` = NULL, `confirm_email_token` = NULL, `confirmed_email` = 1 WHERE email = '$user->email'");
        echo 'OK';
        return;
    }

    public function newPasswordByForgetedPassword()
    {
        helper('funcao');
        $data = $this->request->getPost();

        if (!isset($data['token']))
            return $this->respond(returnVoid($data, 404, "Token não encontrado!"));

        $token = $data['token'];
        $user = $this->db->query("SELECT * FROM utilizadors WHERE reset_token = '$token'")->getRow(0);

        // return $this->respond($user);


        if (strtotime(date('Y-m-d H:i:s')) > strtotime(date($user->api_token_date))) {
            $data1 = [
                'id' => $user->id,
                'reset_token' => md5("12345678910" . date('d-m-Y') . $data['email']),
                'criadopor' => $user->id,
            ];
            updatenormal($this->utilizadorModel, $data1, $this->auditoriaModel);
            return $this->respond(returnVoid($data, 404, "Token Expirado!"));
        }

        $newPassWord = password_hash($data['password'], PASSWORD_BCRYPT);

        if (!is_null($user)) {
            if ($token == $user->reset_token) {
                $data = [
                    'id' => $user->id,
                    'reset_token' => md5($newPassWord . date('d-m-Y') . $data['email']),
                    'password' => $newPassWord,
                    'criadopor' => $user->id,
                ];
                $resposta = updatenormal($this->utilizadorModel, $data, $this->auditoriaModel);
                return $this->respond($resposta, 200);
            }
        }
        return $this->respond(returnVoid($data, 400), 400);
    }

    public function newaccount()
    {
        helper('funcao');

        $data = $this->request->getPost();
        if ($this->existEmail($data['email']))
            return $this->respond(['message' => 'Email existente!'], 403);
        #criacao da conta
        /* $conta = $this->contaModel->save($data); */
        $conta = cadastronormal($this->contaModel, $data, $this->db, $this->auditoriaModel);
        #criacao do proprietário
        $data['conta'] = $conta['id'];
        $proprietario = cadastronormal($this->proprietarioModel, $data, $this->db, $this->auditoriaModel);
        #criacao do utilizador
        /* Acrescentar validação para obrigar password */
        $password =  password_hash(($this->request->getPost('password') == null) ? 1234 : $this->request->getPost('password'), PASSWORD_BCRYPT);

        /* Setting de username */
        $email = $this->request->getPost('email');
        $username = explode("@", $email);

        $data = [
            'proprietario' => $proprietario['id'],
            'estado' => 0,
            'tipo' => 1,
            'username' => $username[0],
            'email' => $email,
            'reset_token' => md5($password . date('d-m-Y') . $email),
            'password' => $password,
            'autenticacao' => $this->request->getPost('autenticacao'),
            'perfil' => 1,
            'ultimoAcesso' => $this->request->getPost('ultimoAcesso'),
            'criadopor' => 1,
            'telefone' => $this->request->getPost('telefone'),
            'acesso' => 1
        ];

        cleanarray($data);

        $user = cadastronormal($this->utilizadorModel, $data, $this->db, $this->auditoriaModel);
        if ($user['code'] !== 200) {
            daletarespecial($proprietario['id'], 1, $this->db, $this->proprietarioModel, $this->auditoriaModel);
            daletarespecial($conta['id'], 1, $this->db, $this->contaModel, $this->auditoriaModel);
            $data['user'] = $user;
            return $this->respond(returnVoid($data, (int) 400), 400);
        }
        return $this->respond($user, 200);
    }

    private function existEmail($email)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM utilizadors WHERE email = '$email'")->getRow(0)->total;
        if ($query > 0)
            return true;
    }
}
