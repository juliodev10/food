<?php

namespace App\Models;

use CodeIgniter\Model;

class EntregadorModel extends Model
{
    protected $table = 'entregadores';
    protected $returnType = 'App\Entities\Entregador';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'nome',
        'cpf',
        'cnh',
        'email',
        'telefone',
        'imagem',
        'ativo',
        'veiculo',
        'placa',
        'endereco',
    ];
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'criado_em';
    protected $updatedField = 'atualizado_em';
    protected $deletedField = 'deletado_em';

    // Validation
    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[120]',
        'email' => 'required|valid_email|is_unique[entregadores.email,id,{id}]',
        'cpf' => 'required|exact_length[14]|validaCpf|is_unique[entregadores.cpf,id,{id}]',
        'cnh' => 'required|exact_length[11]|is_unique[entregadores.cnh,id,{id}]',
        'telefone' => 'required|exact_length[15]|is_unique[entregadores.telefone,id,{id}]',
        'endereco' => 'required|max_length[230]',
        'veiculo' => 'required|max_length[230]',
        'placa' => 'required|min_length[7]|max_length[8]|is_unique[entregadores.placa,id,{id}]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'O campo nome é obrigatório.',
            'min_length' => 'O campo nome deve conter pelo menos 3 caracteres.',
            'max_length' => 'O campo nome deve conter no máximo 120 caracteres.',
        ],
        'email' => [
            'required' => 'O campo email é obrigatório.',
            'valid_email' => 'O campo email deve conter um endereço de email válido.',
            'is_unique' => 'O email informado já está em uso por outro usuário.',
        ],
        'cpf' => [
            'required' => 'O campo CPF é obrigatório.',
            'is_unique' => 'O CPF informado já está em uso por outro usuário.',
            'exact_length' => 'O campo CPF deve conter exatamente 14 caracteres.',
            'validaCpf' => 'Por favor digite um CPF válido.',
        ],
        'password' => [
            'required' => 'O campo senha é obrigatório.',
            'min_length' => 'O campo senha deve conter pelo menos 6 caracteres.',
        ],
        'confirmation_password' => [
            'required_with' => 'O campo de confirmação de senha é obrigatório quando a senha é fornecida.',
            'matches' => 'O campo de confirmação de senha deve corresponder ao campo de senha.',
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
    public function recuperaTotalEntregadoresAtivos()
    {
        return $this->where('ativo', true)->countAllResults();
    }
    public function recuperaEntregadoresMaisAssiduos(int $quantidade)
    {
        return $this->select('entregadores.nome, entregadores.imagem, COUNT(*) AS entregas')
            ->join('pedidos', 'pedidos.entregador_id = entregadores.id')
            ->where('pedidos.situacao', 2) // Considera apenas pedidos entregues
            ->limit($quantidade)
            ->groupBy('entregadores.id, entregadores.nome, entregadores.imagem')
            ->orderBy('entregas', 'DESC')
            ->find();
    }
}
