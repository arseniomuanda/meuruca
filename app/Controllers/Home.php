<?php


namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;

class Home extends ResourceController
{
	protected $login;
	protected $db;

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

		$this->login = new Login();
		$this->db = Database::connect();
	}

	public function websiteresume()
	{
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
		]);;
	}

	public function index()
	{
		$data = $this->request->getVar();
		return $this->respond([
			'Created_at' => "2022-03-29",
			'Time' => date('Y-m-d H:i:s'),
			'Autor' => "Arsénio Muanda",
			'Company' => 'Arpetic',
			'Project' => 'API GESTÃO DE VIATURAS',
			'Data' => $data
		]);
	}
}
