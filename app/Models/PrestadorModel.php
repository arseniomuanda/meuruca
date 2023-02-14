<?php

namespace App\Models;

use CodeIgniter\Model;

class PrestadorModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'prestadors';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['nome', 'nif', 'email', 'telefone', 'endereco', 'criadopor', 'foto', 'site', 'androidlink', 'ioslink', 'gps_latitude', 'gps_longitude', 'w3w', 'country', 'provincia', 'municipio', 'distrito', 'comuna', 'bairro', 'n_casa', 'tipo'];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
}
