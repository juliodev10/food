<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Usuario;
use CodeIgniter\HTTP\ResponseInterface;

class Registrar extends BaseController
{
    private $usuarioModel;
    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }
    public function novo()
    {
        $data = ['titulo' => 'Registrar'];
        return view('Registrar/novo', $data);
    }
    public function criar()
    {
        if ($this->request->is('post')) {
            $this->usuarioModel->desabilitaObrigatoriedadeCpf();
            $usuario = new Usuario($this->request->getPost());
            $this->usuarioModel->desabilitaValidacaoTelefone();
            $usuario->iniciaAtivacao();

            if ($this->usuarioModel->insert($usuario)) {
                $this->enviaEmailParaAtivarConta($usuario);

                return redirect()->to(site_url('registrar/ativacaoenviado'));
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
    public function ativacaoEnviado()
    {
        $data = ['titulo' => 'E-mail de Ativação Enviado'];
        return view('Registrar/ativacao_enviado', $data);
    }
    public function ativar(?string $token = null)
    {
        if ($token === null) {
            return redirect()->to(site_url('login'))->with('atencao', 'Link de ativação inválido');
        }

        $this->usuarioModel->desabilitaObrigatoriedadeCpf();

        if (! $this->usuarioModel->ativarContaPeloToken($token)) {
            return redirect()->to(site_url('login'))->with('atencao', 'Link de ativação inválido ou expirado');
        }

        return redirect()->to(site_url('registrar/ativacaoconcluida'))
            ->with('sucesso', 'Conta ativada com sucesso! Agora você pode fazer login.');
    }

    public function ativacaoConcluida()
    {
        $data = ['titulo' => 'Conta ativada com sucesso'];
        return view('Registrar/ativacao_concluida', $data);
    }
    private function enviaEmailParaAtivarConta(object $usuario)
    {
        $email = service('email');

        $email->setFrom('no-reply@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($usuario->email);
        $email->setSubject('Ativação de Conta - Food Delivery');

        $mensagem = view('Registrar/ativacao_email', ['usuario' => $usuario]);

        $email->setMessage($mensagem);
        $email->send();
    }
}
