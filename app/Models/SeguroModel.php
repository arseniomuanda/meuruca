<?php

namespace App\Models;

use CodeIgniter\Model;

class SeguroModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'seguros';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'viatura', 'bi', 'bi_file', 'livrete_file', 'titulo_file', 'titulo', 'livrete', 'seguradora', 'tipo_seguro', 'nome_contudor', 'referencia', 'comprovante', 'numero_apolice', 'datanascimento_motorista', 'created_at', 'updated_at', 'deleted_at', 'validade', 'estado', 'preferencia',
		'mais_indicado'
	];

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
