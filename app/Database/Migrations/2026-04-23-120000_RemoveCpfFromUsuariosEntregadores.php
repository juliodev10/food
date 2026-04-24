<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveCpfFromUsuariosEntregadores extends Migration
{
    public function up()
    {
        try {
            $this->forge->dropColumn('usuarios', 'cpf');
        } catch (\Throwable $e) {
            // Coluna pode não existir em alguns ambientes.
        }

        try {
            $this->forge->dropColumn('entregadores', 'cpf');
        } catch (\Throwable $e) {
            // Coluna pode não existir em alguns ambientes.
        }
    }

    public function down()
    {
        try {
            $this->forge->addColumn('usuarios', [
                'cpf' => [
                    'type' => 'VARCHAR',
                    'constraint' => 15,
                    'null' => true,
                ],
            ]);
        } catch (\Throwable $e) {
            // Coluna pode já existir em alguns ambientes.
        }

        try {
            $this->forge->addColumn('entregadores', [
                'cpf' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                ],
            ]);
        } catch (\Throwable $e) {
            // Coluna pode já existir em alguns ambientes.
        }
    }
}
