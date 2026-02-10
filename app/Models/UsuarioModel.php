<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $returnType = 'App\Entities\Usuario';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['nome', 'email', 'cpf'];
    protected $useTimestamps = true;

    protected $createdField = 'criado_em'; // Nome da coluna no banco de dados

    protected $updatedField = 'atualizado_em'; // Nome da coluna no banco de dados

    protected $deletedField = 'deletado_em'; // Nome da coluna no banco de dados
    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[120]',
        'email' => 'required|valid_email|is_unique[usuarios.email]',
        'cpf' => 'required|exact_length[14]|is_unique[usuarios.cpf]',
        'password' => 'required|min_length[6]',
        'confirmation_password' => 'required_with[password]|matches[password]',
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
            ->findAll();
    }

    public function desabilitaValidacaoSenha()
    {
        unset($this->validationRules['password']);
        unset($this->validationRules['confirmation_password']);
    }
}
