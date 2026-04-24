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
                'nome' => 'Júlio César França Rodrigues',
                'email' => 'jcking0@hotmail.com',
                'password' => '123456',
                'telefone' => '(35) 99840-7525',
                'is_admin' => true,
                'ativo' => true
            ];

        $usuarioModel->skipValidation(true)->protect(false)->insert($usuario);
    }
}
