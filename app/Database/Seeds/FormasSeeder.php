<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\FormaPagamentoModel;

class FormasSeeder extends Seeder
{
    public function run()
    {
        $formaModel = new FormaPagamentoModel();
        $forma = [
            'nome' => 'Dinheiro',
            'ativo' => true
        ];
        $formaModel->skipValidation(true)->insert($forma);
    }
}
