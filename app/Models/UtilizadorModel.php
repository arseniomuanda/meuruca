<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilizadorModel extends Model
{
    protected $table      = 'utilizadors';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['estado', 'tipo', 'username', 'reset_token', 'email', 'password', 'autenticacao', 'perfil', 'ultimoAcesso', 'criadopor', 'telefone', 'acesso', 'proprietario', 'foto'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [
        'proprietario'              => 'required|max_length[11]|numeric',
        'tipo'              => 'required|max_length[1]|numeric',
        'email'             => 'required|valid_email|max_length[150]',
        'password'          => 'required|max_length[250]',
        'perfil'            => 'required|numeric|max_length[10]',
        'username'        => 'required|min_length[3]|max_length[50]',
        'telefone'          => 'required|min_length[9]|max_length[16]|numeric',
        'acesso'            => 'required|max_length[1]|numeric',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
