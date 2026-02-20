<?php

namespace App\Models;

use CodeIgniter\Model;

class MedidaModel extends Model
{
    protected $table = 'medidas';
    protected $returnType = 'App\Entities\Medida';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'descricao', 'ativo'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    //Validações
    protected $validationRules = [
        'nome' => 'required|min_length[2]|is_unique[medidas.nome]|max_length[120]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório.',
            'max_length' => 'O campo nome deve conter no máximo 120 caracteres.',
            'is_unique' => 'Essa medida já existe.',
        ],
    ];
    public function procurar($term)
    {
        if ($term === null || trim($term) === '') {
            return [];
        }

        return $this->select('id, nome')
            ->like('nome', $term)
            ->withDeleted(true)
            ->get()
            ->getResult();
    }
    public function desfazerExclusao(int $id)
    {
        return $this->protect(false)->where('id', $id)->set('deletado_em', null)->update();
    }
}
