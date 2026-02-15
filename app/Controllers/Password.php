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
            $this->usuarioModel->save($usuario);

            $this->enviaEmailRedefinicaoSenha($usuario);

            return redirect()->to(site_url('login'))->with('info', 'Enviamos um e-mail para você com as instruções para redefinir sua senha');
        } else {
            return redirect()->back();
        }
    }
    public function reset($token = null)
    {
        if ($token === null) {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link inválido ou expirado');
        }
        $usuario = $this->usuarioModel->buscaUsuarioParaResetarSenha($token);
        if ($usuario != null) {
            $data = [
                'titulo' => 'Redefinir minha senha',
                'token' => $token,
            ];
            return view('Password/reset', $data);
        } else {
            return redirect()->to(site_url('password/esqueci'))->with('atencao', 'Link inválido ou expirado');
        }
    }
    private function enviaEmailRedefinicaoSenha(object $usuario)
    {
        $email = service('email');

        $email->setFrom('no-reply@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($usuario->email);
        $email->setSubject('Redefinição de senha - Food Delivery');

        $mensagem = view('Password/reset_email', ['token' => $usuario->reset_token]);

        $email->setMessage($mensagem);
        $email->send();
    }
}
