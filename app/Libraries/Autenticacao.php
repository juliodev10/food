<?php
/*@descricao essa biblioteca / classe cuidará da autenticação de usuários */
namespace App\Libraries;
use App\Entities\Usuario;
use App\Models\UsuarioModel;

class Autenticacao
{
    private $usuario;
    /**
     * Summary of login
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password)
    {
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->buscaUsuarioPorEmail($email);
        if ($usuario === null) {
            return false;
        }
        if (!$usuario->verificaPassword($password)) {
            return false;
        }
        if (!$usuario->ativo) {
            return false;
        }
        $this->logaUsuario($usuario);
        return true;
    }

    public function logout()
    {
        session()->destroy();
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