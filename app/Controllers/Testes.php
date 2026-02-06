<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Testes extends BaseController
{
    public function index()
    {
        $data = [
            'titulo' => 'Título do Testes',
            'teste' => 'Descrição do Testes',
        ];
        return view("Testes/index", $data);
    }
    public function novo()
    {
        echo 'Essa é mais um método novo do controller Testes';
    }
}
