<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Conta extends BaseController
{
    private $usuario;
    private $usuarioModel;
    public function __construct()
    {
        $this->usuario = service('autenticacao')->pegaUsuarioLogado();
        $this->usuarioModel = model('UsuarioModel');
    }
    public function index()
    {
        dd($this->usuario);
    }
    public function show()
    {
        $data = [
            'titulo' => 'Meus Dados',
            'usuario' => $this->usuario
        ];
        return view('Conta/show', $data);
    }
    public function editar()
    {
        if (!session()->has('pode_editar_ate')) {
            return redirect()->to(site_url('conta/autenticar'));
        }
        if (session()->get('pode_editar_ate') < time()) {
            return redirect()->to(site_url('conta/autenticar'));
        }

        $data = [
            'titulo' => 'Editar meus dados',
            'usuario' => $this->usuario
        ];
        return view('Conta/editar', $data);
    }
    public function autenticar()
    {
        $data = [
            'titulo' => 'Autenticar',
            'usuario' => $this->usuario
        ];
        return view('Conta/autenticar', $data);
    }
    public function processaAutenticacao()
    {
        if ($this->request->getMethod() === 'POST') {
            if ($this->usuario->verificaPassword($this->request->getPost('password'))) {
                session()->set('pode_editar_ate', time() + 300); //300 segundos = 5 minutos
                return redirect()->to(site_url('conta/editar'));
            } else {
                return redirect()->back()->with('atencao', 'Senha incorreta. Tente novamente.');
            }
        } else {
            return redirect()->back();
        }
    }
    public function atualizar()
    {
        if ($this->request->getMethod() === 'POST') {
            $this->usuario->fill($this->request->getPost());
            if (!$this->usuario->hasChanged()) {
                return redirect()->back()->with('info', 'Nenhum dado foi alterado.');
            }
            if ($this->usuarioModel->save($this->usuario)) {
                return redirect()->to(site_url("conta/show"))
                    ->with('sucesso', "Seus dados foram atualizados com sucesso");
            } else {
                return redirect()->back()
                    ->with('errors_model', $this->usuarioModel->errors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }
    public function editarSenha()
    {
        $data = [
            'titulo' => 'Alterar minha senha',
            'usuario' => $this->usuario
        ];
        return view('Conta/editar_senha', $data);
    }
    public function atualizarSenha()
    {
        if ($this->request->getMethod() === 'POST') {
            if (!$this->usuario->verificaPassword($this->request->getPost('current_password'))) {
                return redirect()->back()->with('atencao', 'A senha atual está incorreta. Tente novamente.');
            }
            $this->usuario->fill($this->request->getPost());
            if ($this->usuarioModel->save($this->usuario)) {
                return redirect()->to(site_url("conta/show"))
                    ->with('sucesso', "Senha atualizada com sucesso");
            } else {
                return redirect()->back()
                    ->with('errors_model', $this->usuarioModel->errors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }
}
