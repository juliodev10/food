<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AdicionaCanalAcompanhamentoPedidos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pedidos', [
            'canal_acompanhamento' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
                'default' => 'email',
                'after' => 'entregador_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pedidos', 'canal_acompanhamento');
    }
}
