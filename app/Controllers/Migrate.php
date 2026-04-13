<?php

namespace App\Controllers;

use Throwable;

class Migrate extends \CodeIgniter\Controller
{
    public function index()
    {
        $migrate = \Config\Services::migrations();

        try {
            // Executa todas as migrações mais recentes
            if ($migrate->latest()) {
                return "✅ Migrações executadas com sucesso!";
            } else {
                return "ℹ️ Nenhuma migração pendente para executar.";
            }
        } catch (Throwable $e) {
            // Exibe o erro caso algo dê errado (ex: erro de SQL)
            return "❌ Erro ao migrar: " . $e->getMessage();
        }
    }

    /**
     * Reverte a última migração (Rollback).
     * Acesse via: seu-dominio.com/migrate/rollback
     */
    public function rollback()
    {
        $migrate = \Config\Services::migrations();

        try {
            // Volta um "passo" atrás no histórico
            $migrate->regress(-1);
            return "⏪ Rollback executado com sucesso!";
        } catch (Throwable $e) {
            return "❌ Erro ao reverter: " . $e->getMessage();
        }
    }
}
