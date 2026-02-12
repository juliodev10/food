<?php
/*@descricao essa biblioteca / classe cuidará da autenticação de usuários */

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
        $usuarioModel = new App\Models\UsuarioModel();
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
    private function logaUsuario(object $usuario)
    {
        $session = session();
        $session->regenerate();
        $session->set('usuario_id', $usuario->id);
    }
}