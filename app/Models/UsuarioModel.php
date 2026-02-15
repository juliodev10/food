<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Token;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $returnType = 'App\Entities\Usuario';
    protected $allowedFields = ['nome', 'email', 'cpf', 'telefone', 'reset_hash', 'reset_expira_em'];
    //Datas
    protected $useTimestamps = true;
    protected $createdField = 'criado_em'; // Nome da coluna no banco de dados
    protected $updatedField = 'atualizado_em'; // Nome da coluna no banco de dados
    protected $dateFormat = 'datetime'; // Para uso com o $useSoftDeletes
    protected $useSoftDeletes = true;
    protected $deletedField = 'deletado_em'; // Nome da coluna no banco de dados
    //Validações
    protected $validationRules = [
        'nome' => 'required|min_length[3]|max_length[120]',
        'email' => 'required|valid_email|is_unique[usuarios.email]',
        'cpf' => 'required|exact_length[14]|validaCpf|is_unique[usuarios.cpf]',
        'telefone' => 'required|exact_length[15]',
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
    //Eventos callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            unset($data['data']['password']);
            unset($data['data']['confirmation_password']);
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
            ->findAll();
    }

    public function desabilitaValidacaoSenha()
    {
        unset($this->validationRules['password']);
        unset($this->validationRules['confirmation_password']);
    }

    public function desfazerExclusao(int $id)
    {
        return $this->protect(false)->where('id', $id)->set('deletado_em', null)->update();
    }
    public function buscaUsuarioPorEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
    public function buscaUsuarioParaResetarSenha(string $token)
    {
        $token = new Token($token);
        $token_hash = $token->getHash();
        $usuario = $this->where('reset_hash', $token_hash)->first();
        if ($usuario != null) {
            if ($usuario->reset_expira_em < date('Y-m-d H:i:s')) {
                $usuario = null;
            }
            return $usuario;
        }
    }
}
