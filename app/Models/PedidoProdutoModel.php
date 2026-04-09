<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoProdutoModel extends Model
{
    protected $table            = 'pedidos_produtos';
    protected $returnType       = 'object';
    protected $allowedFields    = ['pedido_id', 'produto', 'quantidade'];
}
