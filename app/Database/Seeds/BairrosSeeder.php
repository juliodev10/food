<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\BairroModel;

class BairrosSeeder extends Seeder
{
    public function run()
    {
        $bairroModel = new BairroModel();

        $bairros = [
            [
                'nome' => 'Centro',
                'slug' => 'centro',
                'cidade' => 'Pratápolis',
                'valor_entrega' => 2.00,
                'ativo' => true
            ],
            [
                'nome' => 'Morro Verde',
                'slug' => 'morro-verde',
                'cidade' => 'Pratápolis',
                'valor_entrega' => 15.00,
                'ativo' => true
            ],
        ];

        foreach ($bairros as $bairro) {
            // Verifica se o bairro já existe
            $existente = $bairroModel
                ->where('nome', $bairro['nome'])
                ->where('cidade', $bairro['cidade'])
                ->first();

            if ($existente === null) {
                $bairroModel->skipValidation(true)->insert($bairro);
            }
        }
    }
}
