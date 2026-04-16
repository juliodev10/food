<?php

namespace App\Models;

use CodeIgniter\Model;

class BairroModel extends Model
{
    protected $table = 'bairros';
    protected $returnType = 'App\Entities\Bairro';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'slug', 'cidade', 'valor_entrega', 'ativo'];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';
    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[2]|max_length[120]|bairroCidadeUnico[cidade,id,{id}]',
        'cidade' => 'required|min_length[2]|max_length[20]',
        'valor_entrega' => 'required',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo Nome é obrigatório.',
            'max_length' => 'O campo nome deve conter no máximo 120 caracteres.',
            'bairroCidadeUnico' => 'Esse bairro já existe para essa cidade.',
        ],
        'cidade' => [
            'required' => 'O campo Cidade é obrigatório.',
            'max_length' => 'O campo cidade deve conter no máximo 20 caracteres.',
        ],
        'valor_entrega' => [
            'required' => 'O campo Valor de Entrega é obrigatório.',
            'decimal' => 'O campo Valor de Entrega deve conter um valor decimal válido.',
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
}
