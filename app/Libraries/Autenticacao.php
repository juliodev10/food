<?php
/*@descricao essa biblioteca / classe cuidará da autenticação de usuários */

namespace App\Libraries;

use App\Entities\Usuario;
use App\Models\UsuarioModel;

class Autenticacao
{
    public const FALHA_USUARIO_NAO_ENCONTRADO = 'usuario_nao_encontrado';
    public const FALHA_SENHA_INVALIDA = 'senha_invalida';
    public const FALHA_CONTA_INATIVA = 'conta_inativa';

    private $usuario;
    private ?string $ultimaFalha = null;
    /**
     * Summary of login
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password)
    {
        $this->ultimaFalha = null;

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->buscaUsuarioPorEmail(trim(mb_strtolower($email)));
        if ($usuario === null) {
            $this->ultimaFalha = self::FALHA_USUARIO_NAO_ENCONTRADO;
            return false;
        }
        if (!$usuario->verificaPassword($password)) {
            $this->ultimaFalha = self::FALHA_SENHA_INVALIDA;
            return false;
        }
        if (!$usuario->ativo) {
            $this->ultimaFalha = self::FALHA_CONTA_INATIVA;
            return false;
        }
        $this->logaUsuario($usuario);
        return true;
    }

    public function pegaUltimaFalha(): ?string
    {
        return $this->ultimaFalha;
    }

    public function logout()
    {
        $session = session();
        $session->remove('usuario_id');
        $session->regenerate();
    }

    public function pegaUsuarioLogado()
    {
        if ($this->usuario === null) {
            $this->usuario = $this->pegaUsuarioDaSessao();
        }
        return $this->usuario;
    }

    public function estaLogado()
    {

        return $this->pegaUsuarioLogado() !== null;
    }
    private function pegaUsuarioDaSessao()
    {
        if (!session()->has('usuario_id')) {
            return null;
        }
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->find(session()->get('usuario_id'));
        if ($usuario instanceof Usuario && $usuario->ativo) {
            return $usuario;
        }
    }
    private function logaUsuario(object $usuario)
    {
        $session = session();
        $session->regenerate();
        $session->set('usuario_id', $usuario->id);
    }
}
