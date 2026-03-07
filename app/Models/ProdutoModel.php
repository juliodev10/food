<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table = 'produtos';
    protected $returnType = 'App\Entities\Produto';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'categoria_id',
        'nome',
        'slug',
        'ingredientes',
        'ativo',
        'imagem'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';

    protected $validationRules = [
        'nome' => 'required|min_length[2]|is_unique[produtos.nome,id,{id}]',
        'categoria_id' => 'required|integer',
        'ingredientes' => 'permit_empty|max_length[1000]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório.',
            'max_length' => 'O campo nome deve conter no máximo 120 caracteres.',
            'is_unique' => 'Esse produto já existe.',
        ],
        'categoria_id' => [
            'required' => 'O campo Categoria é obrigatório.',
        ],
        'ingredientes' => [
            'required' => 'O campo Ingredientes é obrigatório.',
            'max_length' => 'O campo ingredientes deve conter no máximo 1000 caracteres.',
            'is_unique' => 'Esse produto já existe.',
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
    public function buscaProdutosWebHome()
    {
        return $this->select(['produtos.id', 'produtos.nome', 'produtos.slug', 'produtos.ingredientes', 'produtos.imagem', 'categorias.id AS categoria_id', 'categorias.nome AS categoria', 'categorias.slug AS categoria_slug'])
            ->selectMin('produtos_especificacoes.preco')
            ->join('categorias', 'categorias.id = produtos.categoria_id')
            ->join('produtos_especificacoes', 'produtos_especificacoes.produto_id = produtos.id')
            ->where('produtos.ativo', true)
            ->groupBy('produtos.nome')
            ->orderBy('categorias.nome', 'ASC')
            ->findAll();
    }
}
