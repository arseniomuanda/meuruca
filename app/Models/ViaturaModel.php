<?php

namespace App\Models;

use CodeIgniter\Model;

class ViaturaModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'viaturas';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = ['matricula', 'numero_apolice', 'apolice', 'livrete', 'titudo_propriedade', 'motorista', 'numero_cartaira', 'cartaira', 'certidao', 'estado_seguro', 'n_chassi', 'cilindrada', 'n_placa', 'n_motor', 'cor', 'created_at', 'updated_at', 'deleted_at', 'proprietario', 'ano', 'descricao', 'imagem', 'placa'];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [
		'matricula' => 'required',
		'ano' => 'required', 
		'proprietario' => 'required',
	];
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
