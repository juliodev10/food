<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'email', 'telefone'];
    protected $useTimestamps = true;

    protected $createdField = 'criado_em'; // Nome da coluna no banco de dados

    protected $updatedField = 'atualizado_em'; // Nome da coluna no banco de dados

    protected $deletedField = 'deletado_em'; // Nome da coluna no banco de dados

    public function procurar($term)
    {
        if ($term === null || trim($term) === '') {
            return [];
        }

        return $this->select('id, nome')
            ->like('nome', $term)
            ->findAll();
    }
}
