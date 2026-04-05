<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Pedido extends Entity
{
    protected $dates   = ['criado_em', 'atualizado_em', 'deletado_em'];

    public function getProdutosPedido(): array
    {
        if (empty($this->produtos)) {
            return [];
        }

        if (is_array($this->produtos)) {
            return $this->produtos;
        }

        $produtos = @unserialize((string) $this->produtos);

        return is_array($produtos) ? $produtos : [];
    }

    public function exibeSituacaoDoPedido()
    {
        switch ($this->situacao) {
            case 0:
                return "<i class='fa fa-thumbs-up fa fa-lg text-success' aria-hidden='true'></i> Pedido realizado";
            case 1:
                return "<i class='fa fa-motorcycle fa fa-lg text-primary' aria-hidden='true'></i> Saiu para entrega";
            case 2:
                return "<i class='fa fa-money fa fa-lg text-success' aria-hidden='true'></i> Pedido entregue";
            case 3:
                return "<i class='fa fa-thumbs-down fa fa-lg text-danger' aria-hidden='true'></i> Pedido cancelado";
            default:
                return '';
        }
    }
}
