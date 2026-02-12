<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Usuario;

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
            'usuarios' => $this->usuarioModel->withDeleted(true)->findAll(),
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

    public function criar()
    {
        $usuario = new Usuario();
        $data = [
            'titulo' => "Cadastrar Novo Usuário",
            'usuario' => new Usuario(),
        ];

        return view('Admin/Usuarios/criar', $data);
    }
    public function cadastrar()
    {
        if (!$this->request->is('post')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Método não permitido");
        }

        $usuario = new Usuario($this->request->getPost());

        if ($this->usuarioModel->protect(false)->save($usuario)) {
            return redirect()->to(site_url("admin/usuarios/show/" . $this->usuarioModel->getInsertID()))->with('sucesso', "Usuário $usuario->nome cadastrado com sucesso");
        } else {
            return redirect()->back()->with('errors_model', $this->usuarioModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')->withInput();
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

        if (!$usuario->hasChanged()) {
            return redirect()->back()->with('info', 'Nenhum dado foi modificado para atualizar.');
        }

        if ($this->usuarioModel->protect(false)->save($usuario)) {
            return redirect()->to(site_url("admin/usuarios/show/$usuario->id"))->with('sucesso', "Usuário $usuario->nome atualizado com sucesso");
        } else {
            return redirect()->back()->with('errors_model', $this->usuarioModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')->withInput();
        }
    }
    public function excluir($id = null)
    {
        $usuario = $this->buscaUsuarioOu404($id);
        if ($usuario->is_admin) {
            return redirect()->back()->with('info', 'Não é permitido excluir um usuário <b>Administrador</b>.');
        }
        if ($this->request->getMethod() === 'POST') {
            $this->usuarioModel->delete($id);
            return redirect()->to(site_url('admin/usuarios'))->with('sucesso', "Usuário $usuario->nome excluído com sucesso!");
        }
        $data = [
            'titulo' => "Excluindo o Usuário $usuario->nome",
            'usuario' => $usuario,
        ];

        return view('Admin/Usuarios/excluir', $data);
    }
    public function desfazerExclusao($id = null)
    {
        $usuario = $this->buscaUsuarioOu404($id);

        if ($usuario->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas usuários excluídos podem ser restaurados.');
        }
        if ($this->usuarioModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', "Exclusão do usuário $usuario->nome desfeita com sucesso!");
        } else {
            return redirect()->back()->with('errors_model', $this->usuarioModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')->withInput();
        }
    }
    private function buscaUsuarioOu404($id = null)
    {
        if (!$id || !$usuario = $this->usuarioModel->withDeleted(true)->where('id', $id)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o usuário $id");
        }
        return $usuario;
    }
}
