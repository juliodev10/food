<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    private $pedidoModel;
    private $usuarioModel;
    private $entregadorModel;
    private $pedidoProdutoModel;

    public function __construct()
    {
        $this->pedidoModel = new \App\Models\PedidoModel();
        $this->usuarioModel = new \App\Models\UsuarioModel();
        $this->entregadorModel = new \App\Models\EntregadorModel();
        $this->pedidoProdutoModel = new \App\Models\PedidoProdutoModel();
    }
    public function index()
    {
        helper('empresa');
        $expedienteHoje = expedienteHoje();

        $data = [
            'titulo' => 'Home da área restrita',
            'valorPedidosEntregues' => $this->pedidoModel->valorPedidosEntregues(),
            'valorPedidosCancelados' => $this->pedidoModel->valorPedidosCancelados(),
            'totalClientesAtivos' => $this->usuarioModel->recuperaTotalClientesAtivos(),
            'totalEntregadoresAtivos' => $this->entregadorModel->recuperaTotalEntregadoresAtivos(),
            'empresaAbertaAgora' => $this->empresaAbertaAgora($expedienteHoje),
            'produtosMaisVendidos' => $this->pedidoProdutoModel->recuperaProdutosMaisVendidos(5),
            'clientesMaisAssiduos' => $this->pedidoModel->recuperaClientesMaisAssiduos(5),
            'entregadoresMaisAssiduos' => $this->entregadorModel->recuperaEntregadoresMaisAssiduos(5),
        ];
        $novosPedidos = $this->pedidoModel
            ->select('pedidos.*, COALESCE(pedidos.valor_pedido, (pedidos.valor_produtos + pedidos.valor_entrega), 0) AS valor_total', false)
            ->where('situacao', 2)
            ->orderBy('criado_em', 'desc')
            ->findAll(5);
        $data['novosPedidos'] = $novosPedidos;
        return view('Admin/Home/index', $data);
    }

    private function empresaAbertaAgora($expedienteHoje): bool
    {
        if ($expedienteHoje === null) {
            return false;
        }

        $abertura = $expedienteHoje->abertura ?? null;
        $fechamento = $expedienteHoje->fechamento ?? null;

        if (empty($abertura) || empty($fechamento)) {
            return false;
        }

        $horaAtual = date('H:i:s');
        $horaAbertura = date('H:i:s', strtotime((string) $abertura));
        $horaFechamento = date('H:i:s', strtotime((string) $fechamento));

        if ($horaAbertura <= $horaFechamento) {
            return $horaAtual >= $horaAbertura && $horaAtual <= $horaFechamento;
        }

        return $horaAtual >= $horaAbertura || $horaAtual <= $horaFechamento;
    }
}
