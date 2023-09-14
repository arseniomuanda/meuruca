<?php

namespace App\Models;

use CodeIgniter\Model;

class CaracteristicaModel extends Model
{
    protected $table      = 'caracteristicas';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['referencia', 'tabela', 'item', 'descricao'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
