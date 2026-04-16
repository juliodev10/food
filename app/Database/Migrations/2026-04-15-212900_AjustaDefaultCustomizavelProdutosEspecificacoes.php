<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AjustaDefaultCustomizavelProdutosEspecificacoes extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE produtos_especificacoes MODIFY customizavel TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE produtos_especificacoes MODIFY customizavel TINYINT(1) NOT NULL');
    }
}
