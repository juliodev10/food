<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table            = 'pedidos';
    protected $returnType       = 'App\Entities\Pedido';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'usuario_id',
        'entregador_id',
        'canal_acompanhamento',
        'codigo',
        'forma_pagamento',
        'situacao',
        'produtos',
        'valor_produtos',
        'valor_entrega',
        'valor_pedido',
        'endereco_entrega',
        'observacoes',
    ];
    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'deletado_em';

    public function geraCodigoPedido()
    {
        do {
            $codigoPedido = random_string('numeric', 8);
            $this->where('codigo', $codigoPedido);
        } while ($this->countAllResults() > 1);
        return $codigoPedido;
    }
    public function procurar($term)
    {
        if ($term === null || trim($term) === '') {
            return [];
        }

        return $this->select('codigo')
            ->like('codigo', $term)
            ->withDeleted(true)
            ->get()
            ->getResult();
    }
    public function listaTodosOsPedidos()
    {
        return $this->select(
            [
                'pedidos.*',
                'usuarios.nome as cliente',
                'COALESCE(pedidos.valor_pedido, (pedidos.valor_produtos + pedidos.valor_entrega), 0) as valor_total',
            ]
        )
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id')
            ->withDeleted(true)
            ->orderBy('pedidos.criado_em', 'DESC')
            ->paginate(10);
    }
    public function desfazerExclusao(int $id)
    {
        return $this->protect(false)->where('id', $id)->set('deletado_em', null)->update();
    }
    public function buscaPedidoOu404(string $codigoPedido)
    {
        if (!$codigoPedido) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pedido não encontrado');
        }
        $pedido = $this->select(['pedidos.*', 'usuarios.nome', 'usuarios.email', 'usuarios.telefone', 'entregadores.nome as entregador'])
            ->join('entregadores', 'entregadores.id = pedidos.entregador_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id')
            ->withDeleted(true)
            ->where('pedidos.codigo', $codigoPedido)
            ->first();
        if (!$pedido) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pedido não encontrado');
        }
        return $pedido;
    }
}
