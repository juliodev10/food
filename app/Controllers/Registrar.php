<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Registrar extends BaseController
{
    public function novo()
    {
        $data = ['titulo' => 'Registrar'];
        return view('Registrar/novo', $data);
    }
}
