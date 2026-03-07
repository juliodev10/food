<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table = 'categorias';
    protected $returnType = 'App\Entities\Categoria';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'ativo', 'slug'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[120]|is_unique[categorias.nome,id,{id}]|categoriaUnica[id]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório.',
            'max_length' => 'O campo nome deve conter no máximo 120 caracteres.',
            'is_unique' => 'Essa categoria já existe.',
            'categoriaUnica' => 'Essa categoria já existe.',
        ],
    ];
    //Eventos callbacks
    protected $beforeInsert = ['criaSlug'];
    protected $beforeUpdate = ['criaSlug'];
    protected function criaSlug(array $data)
    {
        if (isset($data['data']['nome'])) {
            $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', true);
        }
        return $data;
    }
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
    public function BuscaCategoriasWebHome()
    {
        return $this->select('categorias.id, categorias.nome, categorias.slug')
            ->where('categorias.ativo', true)
            ->orderBy('categorias.nome', 'ASC')
            ->findAll();
    }
}
