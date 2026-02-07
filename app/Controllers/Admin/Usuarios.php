<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Usuarios extends BaseController
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    public function index()
    {
        $data = [
            'titulo' => 'Lista de Usuários',
            'usuarios' => $this->usuarioModel->findAll(),
        ];

        return view('Admin/Usuarios/index', $data);
    }

    public function procurar()
    {
        $usuarios = $this->usuarioModel->procurar($this->request->getGet('term'));
        $retorno = [];

        foreach ($usuarios as $usuario) {
            $retorno[] = [
                'id' => $usuario->id,
                'label' => $usuario->nome,
                'value' => $usuario->nome
            ];
        }

        return $this->response->setJSON($retorno);
    }
}
