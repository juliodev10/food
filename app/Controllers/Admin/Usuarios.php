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
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada');
        }

        $usuarios = $this->usuarioModel->procurar($this->request->getGet('term'));

        $retorno = [];

        foreach ($usuarios as $usuario) {
            $retorno[] = [
                'id' => $usuario->id,
                'value' => $usuario->nome,
                'email' => $usuario->email,
                'cpf' => $usuario->cpf
            ];
        }

        return $this->response->setJSON($retorno);
    }
}
