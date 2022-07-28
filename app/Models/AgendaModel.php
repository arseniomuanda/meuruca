<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendaModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'agendas';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['ticket', 'inicio', 'fim', 'descricao', 'activo', 'estado', 'nome_item', 'viatura', 'prestador', 'is_domicilio', 'servico_entrega', 'categoria'];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	/* protected $validationRules      = [
		'ticket' => 'required|max_length', 
		'inicio', 
		'fim', 
		'descricao', 
		'activo', 
		'estado'
	]; */
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
