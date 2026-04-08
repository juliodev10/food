<?php

namespace App\Controllers\Admin;

class Pedidos extends \App\Controllers\AdminPedidos
{
    private $pedidoModel;
    private $entregadorModel;
    public function __construct()
    {
        $this->pedidoModel = new \App\Models\PedidoModel();
        $this->entregadorModel = new \App\Models\EntregadorModel();
    }
    public function index()
    {
        $data = [
            'titulo' => 'Pedidos realizados',
            'pedidos' => $this->pedidoModel->listaTodosOsPedidos(),
            'pager' => $this->pedidoModel->pager,
        ];
        return view('Admin/Pedidos/index', $data);
    }
    public function show($codigoPedido = null)
    {
        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
        $data = [
            'titulo' => 'Detalhando pedido: ' . $pedido->codigo,
            'pedido' => $pedido,
        ];
        return view('Admin/Pedidos/show', $data);
    }
    public function editar($codigoPedido = null)
    {
        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
        if ($pedido->situacao == 2) {
            return redirect()->back()->with('info', 'Não é possível editar um pedido que já foi entregue.');
        }
        if ($pedido->situacao == 3) {
            return redirect()->back()->with('info', 'Não é possível editar um pedido que já foi cancelado.');
        }
        $data = [
            'titulo' => 'Detalhando pedido: ' . $pedido->codigo,
            'pedido' => $pedido,
            'entregadores' => $this->entregadorModel->select('id, nome')->where('ativo', true)->findAll(),
        ];
        return view('Admin/Pedidos/editar', $data);
    }
    public function atualizar($codigoPedido = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
            if ($pedido->situacao == 2) {
                return redirect()->back()->with('info', 'Não é possível editar um pedido que já foi entregue.');
            }
            if ($pedido->situacao == 3) {
                return redirect()->back()->with('info', 'Não é possível editar um pedido que já foi cancelado.');
            }
            $pedidoPost = $this->request->getPost();
            if (!isset($pedidoPost['situacao'])) {
                return redirect()->back()->with('atencao', 'A situação do pedido é obrigatória.');
            }
            if ($pedidoPost['situacao'] == 1) {
                if (strlen($pedidoPost['entregador_id']) < 1) {
                    return redirect()->back()->with('atencao', 'O entregador é obrigatório quando o pedido saiu para entrega.');
                }
            }
            // Não permite marcar como entregue (situacao 2) sem ter saído para entrega
            if ($pedido->situacao == 0 && $pedidoPost['situacao'] == 2) {
                return redirect()->back()->with('atencao', 'O pedido não pode ser marcado como entregue sem antes ter <strong>saido para entrega.</strong>');
            }
            if ($pedidoPost['situacao'] != 1) {
                unset($pedidoPost['entregador_id']);
            }
            if ($pedidoPost['situacao'] == 3) {
                $pedidoPost['entregador_id'] = null;
            }

            $situacaoAnteriorPedido = $pedido->situacao;
            $pedido->fill($pedidoPost);
            if (!$pedido->hasChanged()) {
                return redirect()->back()->with('info', 'Nenhuma alteração foi feita no pedido.');
            }

            if ($this->pedidoModel->save($pedido)) {
                if ($pedido->situacao == 1 && $situacaoAnteriorPedido != 1) {
                    $entregador = $this->entregadorModel->find($pedido->entregador_id);
                    $pedido->entregador = $entregador;
                    $this->enviaEmailPedidoSaiuEntrega($pedido);
                }
                return redirect()->back()->with('sucesso', 'Pedido atualizado com sucesso.');
            } else {
                return redirect()->back()->with('errors_model', $this->pedidoModel->errors())->with('atencao', 'Por favor, verifique os erros abaixo e tente novamente.');
            }
        } else {
            return redirect()->back();
        }
    }
    private function enviaEmailPedidoSaiuEntrega(object $pedido)
    {
        $email = service('email');

        $email->setFrom('no-reply@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($pedido->email);
        $email->setSubject("Pedido {$pedido->codigo} saiu para entrega");

        $mensagem = view('Admin/Pedidos/pedido_saiu_entrega_email', ['pedido' => $pedido]);

        $email->setMessage($mensagem);
        $email->send();
    }
}
