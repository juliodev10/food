<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\FormaPagamentoModel;

class FormasSeeder extends Seeder
{
    public function run()
    {
        $formaModel = new FormaPagamentoModel();

        $formas = [
            [
                'nome' => 'Dinheiro',
                'ativo' => true,
            ],
            [
                'nome' => 'Pix',
                'ativo' => true,
            ],
        ];

        foreach ($formas as $forma) {
            $existente = $formaModel->where('nome', $forma['nome'])->first();

            if ($existente === null) {
                $formaModel->skipValidation(true)->insert($forma);
            }
        }
    }
}
