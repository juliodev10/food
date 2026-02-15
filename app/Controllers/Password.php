<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Password extends BaseController
{
    private $usuarioModel;
    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }
    public function esqueci()
    {
        $data = [
            'titulo' => 'Esqueci minha senha',
        ];
        return view('Password/esqueci', $data);
    }
    public function processaEsqueci()
    {
        if ($this->request->getMethod() === 'POST') {
            $usuario = $this->usuarioModel->buscaUsuarioPorEmail($this->request->getPost('email'));
            if ($usuario === null || !$usuario->ativo) {
                return redirect()->back()
                    ->with('atencao', 'Não encontramos uma conta válida com esse email')->withInput();
            }
            $usuario->iniciaPasswordReset();
            dd($usuario);
        } else {
            return redirect()->back();
        }
    }
}