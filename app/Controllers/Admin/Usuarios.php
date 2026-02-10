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
            $data['id'] = $usuario->id;
            $data['value'] = $usuario->nome;
            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }

    public function atualizar($id = null)
    {
        if (!$this->request->is('post')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Método não permitido");
        }

        $usuario = $this->buscaUsuarioOu404($id);
        $post = $this->request->getPost();
        if (empty($post['password'])) {
            $this->usuarioModel->desabilitaValidacaoSenha();
            unset($post['password']);
            unset($post['confirmation_password']);
        }

        $usuario->fill($post);

        if ($this->usuarioModel->protect(false)->save($usuario)) {
            return redirect()->to(site_url("admin/usuarios/show/$usuario->id"))->with('success', "Usuário $usuario->nome atualizado com sucesso");
        } else {
            return redirect()->back()->with('errors_model', $this->usuarioModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!');
        }
    }

    public function show($id = null)
    {
        $usuario = $this->buscaUsuarioOu404($id);

        $data = [
            'titulo' => "Detalhes do Usuário $usuario->nome",
            'usuario' => $usuario,
        ];

        return view('Admin/Usuarios/show', $data);
    }
    public function editar($id = null)
    {
        $usuario = $this->buscaUsuarioOu404($id);

        $data = [
            'titulo' => "Editar Usuário $usuario->nome",
            'usuario' => $usuario,
        ];

        return view('Admin/Usuarios/editar', $data);
    }

    private function buscaUsuarioOu404($id = null)
    {
        if (!$id || !$usuario = $this->usuarioModel->where('id', $id)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o usuário $id");
        }
        return $usuario;
    }
}
