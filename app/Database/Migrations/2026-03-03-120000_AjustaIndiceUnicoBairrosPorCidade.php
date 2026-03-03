<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AjustaIndiceUnicoBairrosPorCidade extends Migration
{
    public function up()
    {
        $db = $this->db;

        try {
            $db->query('ALTER TABLE bairros DROP INDEX bairros_nome');
        } catch (\Throwable $e) {
            try {
                $db->query('ALTER TABLE bairros DROP INDEX nome');
            } catch (\Throwable $e) {
            }
        }

        $db->query('ALTER TABLE bairros ADD UNIQUE KEY bairros_nome_cidade_unique (nome, cidade)');
    }

    public function down()
    {
        $db = $this->db;

        try {
            $db->query('ALTER TABLE bairros DROP INDEX bairros_nome_cidade_unique');
        } catch (\Throwable $e) {
        }

        $db->query('ALTER TABLE bairros ADD UNIQUE KEY bairros_nome_unique (nome)');
    }
}
