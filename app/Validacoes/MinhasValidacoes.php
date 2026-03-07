<?php
namespace App\Validacoes;

use Config\Database;

class MinhasValidacoes
{
    public function validaCpf(string $cpf, ?string &$error = null): bool
    {
        // 1. Deixa apenas os números
        $cpf = preg_replace('/\D/', '', $cpf);

        // 2. Verifica se tem 11 dígitos ou se é uma sequência repetida (ex: 111.111.111-11)
        // A regex /(\d)\1{10}/ verifica se um mesmo número se repete 11 vezes
        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            $error = 'Por favor digite um CPF válido';
            return false;
        }

        // 3. Faz o cálculo matemático dos dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                $error = 'Por favor digite um CPF válido';
                return false;
            }
        }

        return true;
    }

    public function bairroCidadeUnico(string $nome, string $fields, array $data, ?string &$error = null): bool
    {
        [$campoCidade, $campoId, $valorId] = array_pad(explode(',', $fields), 3, null);

        if (!$campoCidade || empty($data[$campoCidade])) {
            return true;
        }

        $cidade = (string) $data[$campoCidade];
        $id = null;

        if ($campoId && isset($data[$campoId])) {
            $id = (int) $data[$campoId];
        }

        if (!$id && $valorId !== null && $valorId !== '') {
            $valorId = trim((string) $valorId);

            if (preg_match('/^\{(.+)\}$/', $valorId, $matches) === 1) {
                $campoPlaceholder = $matches[1];
                if (isset($data[$campoPlaceholder])) {
                    $id = (int) $data[$campoPlaceholder];
                }
            } else {
                $id = (int) $valorId;
            }
        }

        if (!$id) {
            $uriId = (int) service('request')->getUri()->getSegment(4);
            if ($uriId > 0) {
                $id = $uriId;
            }
        }

        $db = Database::connect();
        $builder = $db->table('bairros')
            ->select('id')
            ->where('nome', $nome)
            ->where('cidade', $cidade)
            ->where('deletado_em', null);

        if ($id) {
            $builder->where('id !=', $id);
        }

        if ($builder->countAllResults() > 0) {
            $error = 'Esse bairro já existe para essa cidade.';
            return false;
        }

        return true;
    }

    public function categoriaUnica(string $nome, string $fields, array $data, ?string &$error = null): bool
    {
        $campoId = trim($fields);
        $id = null;

        if ($campoId !== '' && isset($data[$campoId])) {
            $id = (int) $data[$campoId];
        }

        if (!$id) {
            $uriId = (int) service('request')->getUri()->getSegment(4);
            if ($uriId > 0) {
                $id = $uriId;
            }
        }

        $nomeNormalizado = mb_strtolower(trim($nome), 'UTF-8');

        $db = Database::connect();
        $builder = $db->table('categorias')
            ->select('id, nome')
            ->where('deletado_em', null);

        if ($id) {
            $builder->where('id !=', $id);
        }

        $categorias = $builder->get()->getResult();

        foreach ($categorias as $categoria) {
            if (mb_strtolower(trim((string) $categoria->nome), 'UTF-8') === $nomeNormalizado) {
                $error = 'Essa categoria já existe.';
                return false;
            }
        }

        return true;
    }
}