<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function novo()
    {
        $data = [
            'titulo' => 'Login',
        ];
        return view('Login/novo', $data);
    }
    public function criar()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = trim((string) $this->request->getPost('email'));
            $password = (string) $this->request->getPost('password');

            $autenticacao = service('autenticacao');

            if ($autenticacao->login($email, $password)) {
                $usuario = $autenticacao->pegaUsuarioLogado();

                if (!$usuario->is_admin) {
                    if (session()->has('carrinho')) {
                        return redirect()->to(site_url('checkout'))->with('sucesso', "Seja bem-vindo(a)! $usuario->nome");
                    }
                    return redirect()->to(site_url('/'));
                }
                return redirect()->to(site_url('admin/home'))->with('sucesso', "Seja bem-vindo(a)! $usuario->nome");
            } else {
                if ($autenticacao->pegaUltimaFalha() === \App\Libraries\Autenticacao::FALHA_CONTA_INATIVA) {
                    return redirect()->back()->with('atencao', 'Sua conta ainda não foi ativada. Verifique seu e-mail e clique no link de ativação.');
                }

                return redirect()->back()->with('atencao', 'Não temos suas credenciais de acesso.');
            }
        } else {
            return redirect()->back();
        }
    }
    public function logout()
    {
        service('autenticacao')->logout();
        return redirect()->to(site_url('login'))->with('info', 'Esperamos ver você novamente');
    }
    public function mostraMensagemLogout()
    {
        return redirect()->to(site_url('login'))->with('info', 'Esperamos ver você novamente');
    }
}
