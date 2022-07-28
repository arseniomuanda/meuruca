<?php
namespace App\Models;

use CodeIgniter\Model;

class AuditoriaModel extends Model
{
    protected $table      = 'auditoria';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['accao', 'processo', 'registo', 'utilizador', 'dataAcao', 'dataExpiracao'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [
        'accao'         => 'required', 
        'processo'      => 'required', 
        'registo'       => 'required', 
        'utilizador'    => 'required', 
        'dataAcao'      => 'required', 
        'dataExpiracao' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}