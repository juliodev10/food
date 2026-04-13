<?php

namespace App\Controllers;

use Throwable;

class Seed extends BaseController
{
    public function index()
    {
        $seeder = \Config\Database::seeder();
        $seeder->call('UsuarioSeeder');
        $seeder->call('BairrosSeeder');
        $seeder->call('ExpedienteSeeder');
        $seeder->call('FormasSeeder');
        echo "✅ Seeders executados com sucesso!";
    }
}
