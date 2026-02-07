<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $usuarioModel = new \App\Models\UsuarioModel();

        $usuarios = [
            [
                'nome' => 'João Silva',
                'email' => 'joao@exemplo.com',
                'cpf' => '722.508.120-98',
                'telefone' => '(11) 98765-4321',
                'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'ativo' => true,
                'is_admin' => false,
            ],
            [
                'nome' => 'Maria Santos',
                'email' => 'maria@exemplo.com',
                'cpf' => '123.456.789-00',
                'telefone' => '(21) 99876-5432',
                'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'ativo' => true,
                'is_admin' => false,
            ],
            [
                'nome' => 'Pedro Oliveira',
                'email' => 'pedro@exemplo.com',
                'cpf' => '987.654.321-00',
                'telefone' => '(31) 91234-5678',
                'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
                'ativo' => false,
                'is_admin' => false,
            ],
        ];

        // Inserir dados na tabela usuarios
        $usuarioModel->protect(false)->insertBatch($usuarios);
    }
}