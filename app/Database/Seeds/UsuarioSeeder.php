<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nome' => 'João Silva',
                'email' => 'joao@exemplo.com',
                'telefone' => '(11) 98765-4321',
                'ativo' => true,
            ],
            [
                'nome' => 'Maria Santos',
                'email' => 'maria@exemplo.com',
                'telefone' => '(21) 99876-5432',
                'ativo' => true,
            ],
            [
                'nome' => 'Pedro Oliveira',
                'email' => 'pedro@exemplo.com',
                'telefone' => '(31) 91234-5678',
                'ativo' => false,
            ],
        ];

        // Inserir dados na tabela usuarios
        $this->db->table('usuarios')->insertBatch($data);
    }
}
