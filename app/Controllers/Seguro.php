<?php

namespace App\Controllers;

use App\Models\AuditoriaModel;
use App\Models\SeguroModel;
use App\Models\ViaturaModel;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use phpDocumentor\Reflection\Types\This;

class Seguro extends ResourceController
{
	protected	$model;
	protected $db;
	protected $auditoria;
	protected $viatura;

	function __construct()
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

		$this->model = new SeguroModel();
		$this->db = Database::connect();
		$this->auditoria = new AuditoriaModel();
		$this->viatura = new ViaturaModel();
	}
	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	public function index()
	{
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		if (!is_null($id)) {
			$data = $this->model->where('id', $id)->first();
		} else {
			$data = $this->model->paginate();
		}
		return $this->respond($data);
	}

	public function estado($type)
	{
		$data = $this->model->where('estado', $type)->paginate();
		return $this->respond($data);
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		helper('funcao');
		$bi_file = $this->request->getFile('bi_file');
			$livrete_file = $this->request->getFile('livrete_file');
			$titulo_file = $this->request->getFile('titulo_file');
		$data = [
			'viatura' => $this->request->getPost('viatura'),
			'bi' => $this->request->getPost('bi'),
			'titulo' => $this->request->getPost('titulo'),
			'livrete' => $this->request->getPost('livrete'),
			'seguradora' => $this->request->getPost('seguradora'),
			'tipo_seguro' => $this->request->getPost('tipo_seguro'),
			'nome_contudor' => $this->request->getPost('nome_contudor'),
			'referencia' => $this->request->getPost('referencia'),
			'comprovante' => $this->request->getPost('comprovante'),
			'numero_apolice' => $this->request->getPost('numero_apolice'),
			'datanascimento_motorista' => $this->request->getPost('datanascimento_motorista'),
			'validade' => $this->request->getPost('validade'),
			'preferencia' => $this->request->getPost('preferencia'),
			'mais_indicado' => $this->request->getPost('mais_indicado'),
			'criadopor' => 1,
			'estado' => 0
		];
		$data = cleanarray($data);
		$resposta = cadastrocomseisfotos($this->model, $data, $this->db, $this->auditoria, 'Seguro', $bi_file, 'bi_file', $livrete_file, 'livrete_file', $titulo_file, 'titulo_file', null, null, null, null, null, null);
		
		
		if ($resposta['code'] !== 200) {
			return $this->respond(returnVoid($data, (int) 400), 400);
		}

		$data2 = [
			'id' => $this->request->getPost('viatura'), 
			'numero_apolice' => $this->request->getPost('numero_apolice'), 
			'apolice' => $this->request->getPost('apolice'), 
			'livrete' => $this->request->getPost('livrete'), 
			'titudo_propriedade' => $this->request->getPost('titulo'),
			'motorista' => $this->request->getPost('nome_contudor'), 
			'numero_cartaira' => $this->request->getPost('numero_cartaira'), 
			'cartaira' => $this->request->getPost('cartaira'), 
			'certidao' => $this->request->getPost('certidao'), 
			'estado_seguro' => $this->request->getPost('estado_seguro'), 
			'n_chassi' => $this->request->getPost('n_chassi'), 
			'cilindrada' => $this->request->getPost('cilindrada'), 
			'n_placa' => $this->request->getPost('n_placa'), 
			'n_motor' => $this->request->getPost('n_motor'),
			'cor' => $this->request->getPost('cor'), 
			'created_at' => $this->request->getPost('created_at'), 
			'updated_at' => $this->request->getPost('updated_at'), 
			'deleted_at' => $this->request->getPost('deleted_at'), 
			'proprietario' => $this->request->getPost('proprietario'), 
			'ano' => $this->request->getPost('ano'), 
			'descricao' => $this->request->getPost('descricao'), 
			'imagem' => $this->request->getPost('imagem'),
			'criadopor' => 1
		];
		$data2 = cleanarray($data2);
		updatenormal($this->viatura, $data2, $this->auditoria);
		return $this->respond($resposta);
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create()
	{
		//
	}

	/**
	 * Return the editable properties of a resource object
	 *
	 * @return mixed
	 */
	public function edit($id = null)
	{
		//
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @return mixed
	 */
	public function update($id = null)
	{
		helper('funcao');
		$bi_file = $this->request->getFile('bi_file');
		$livrete_file = $this->request->getFile('livrete_file');
		$titulo_file = $this->request->getFile('titulo_file');
		$data = [
			'id' => $id,
			'viatura' => $this->request->getPost('viatura'),
			'bi' => $this->request->getPost('bi'),
			'titulo' => $this->request->getPost('titulo'),
			'livrete' => $this->request->getPost('livrete'),
			'seguradora' => $this->request->getPost('seguradora'),
			'tipo_seguro' => $this->request->getPost('tipo_seguro'),
			'nome_contudor' => $this->request->getPost('nome_contudor'),
			'referencia' => $this->request->getPost('referencia'),
			'comprovante' => $this->request->getPost('comprovante'),
			'numero_apolice' => $this->request->getPost('numero_apolice'),
			'datanascimento_motorista' => $this->request->getPost('datanascimento_motorista'),
			'validade' => $this->request->getPost('validade'),
			'data_validade' => $this->request->getPost('data_validade'),
			'preferencia' => $this->request->getPost('preferencia'),
			'mais_indicado' => $this->request->getPost('mais_indicado'),
			'criadopor' => 1,
			'estado' => $this->request->getPost('estado'),
		];
		$data = cleanarray($data);
		$resposta = cadastrocomseisfotos($this->model, $data, $this->db, $this->auditoria, 'Seguro', $bi_file, 'bi_file', $livrete_file, 'livrete_file', $titulo_file, 'titulo_file', null, null, null, null, null, null);


		if ($resposta['code'] !== 200) {
			return $this->respond(returnVoid($data, (int) 400), 400);
		}
		return $this->respond($resposta);
	}

	/**
	 * Delete the designated resource object from the model
	 *
	 * @return mixed
	 */
	public function delete($id = null)
	{
		//
	}
}
