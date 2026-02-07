<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $usuarioModel = new \App\Models\UsuarioModel;

        $usuario =
            [
                'nome' => 'João Silva',
                'email' => 'joao@exemplo.com',
                'telefone' => '(11) 98765-4321',
            ];

        $usuarioModel->protect(false)->insert($usuario);

        $usuario =
            [
                'nome' => 'Maria Santos',
                'email' => 'maria@exemplo.com',
                'telefone' => '(21) 99876-5432',
            ];

        // Inserir dados na tabela usuarios
        $usuarioModel->protect(false)->insert($usuario);

        dd($usuarioModel->errors());
    }
}