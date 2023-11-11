<?php

namespace App\Controllers;

use App\Models\AuditoriaModel;
use App\Models\Seguradora as ModelsSeguradora;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class Seguradora extends ResourceController
{
	protected $model;
	protected $db;
	protected $auditoria;

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

		$this->model = new ModelsSeguradora();
		$this->db = Database::connect();
		$this->auditoria = new AuditoriaModel();
	}


	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	public function index()
	{
		//
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		//
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		helper('funcao');
		$logo = $this->request->getFile('logo');
		$data = [
			'name' => $this->request->getPost('name'),
			'description' => $this->request->getPost('description'),
			'estado' => $this->request->getPost('estado'),
			'is_favorito' => $this->request->getPost('is_favorito'),
			'criadopor' => 1
		];

		$data = cleanarray($data);
		$resposta = cadastrocomumafoto($this->model, $data, $this->db, $this->auditoria, $logo, 'logo');
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
		$logo = $this->request->getFile('logo');
		$data = [
			'id' => $id,
			'name' => $this->request->getPost('name'),
			'description' => $this->request->getPost('description'),
			'estado' => $this->request->getPost('estado'),
			'is_favorito' => $this->request->getPost('is_favorito'),
			'criadopor' => 1
		];

		$data = cleanarray($data);
		$resposta = updatecomumafoto($this->model, $data, $this->db, $this->auditoria, 'Seguradora', $this->model->table, $logo, 'logo');
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
