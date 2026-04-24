<?php

namespace App\Controllers\Admin;

class Pedidos extends \App\Controllers\AdminPedidos
{
    private $pedidoModel;
    private $entregadorModel;
    private $usuarioModel;
    public function __construct()
    {
        $this->pedidoModel = new \App\Models\PedidoModel();
        $this->entregadorModel = new \App\Models\EntregadorModel();
        $this->usuarioModel = new \App\Models\UsuarioModel();
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
    public function procurar()
    {
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada');
        }
        // Certifique-se que o método 'procurar' existe no seu bairroModel
        $pedidos = $this->pedidoModel->procurar($this->request->getGet('term'));
        $retorno = [];

        foreach ($pedidos as $pedido) {
            $data['value'] = $pedido->codigo;
            $retorno[] = $data;
        }
        return $this->response->setJSON($retorno);
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
        $redirectShow = redirect()->to(site_url("admin/pedidos/show/$codigoPedido"));

        if ($this->request->getMethod() === 'POST') {
            $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
            if ($pedido->situacao == 2) {
                return $redirectShow->with('info', 'Não é possível editar um pedido que já foi entregue.');
            }
            if ($pedido->situacao == 3) {
                return $redirectShow->with('info', 'Não é possível editar um pedido que já foi cancelado.');
            }
            $pedidoPost = $this->request->getPost();
            if (!isset($pedidoPost['situacao'])) {
                return $redirectShow->with('atencao', 'A situação do pedido é obrigatória.');
            }
            if ($pedidoPost['situacao'] == 1) {
                if (strlen($pedidoPost['entregador_id']) < 1) {
                    return $redirectShow->with('atencao', 'O entregador é obrigatório quando o pedido saiu para entrega.');
                }
            }
            // Não permite marcar como entregue (situacao 2) sem ter saído para entrega
            if ($pedido->situacao == 0 && $pedidoPost['situacao'] == 2) {
                return $redirectShow->with('atencao', 'O pedido não pode ser marcado como entregue sem antes ter <strong>saido para entrega.</strong>');
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
                return $redirectShow->with('info', 'Nenhuma alteração foi feita no pedido.');
            }

            if ($this->pedidoModel->save($pedido)) {
                $this->insereProdutosDoPedido($pedido);

                $mensagemSucesso = 'Pedido atualizado com sucesso.';

                if ($pedido->situacao == 1 && $situacaoAnteriorPedido != 1) {
                    $entregador = $this->entregadorModel->find($pedido->entregador_id);
                    $pedido->entregador = $entregador;

                    if (($pedido->canal_acompanhamento ?? 'email') === 'whatsapp') {
                        $linkWhatsapp = $this->geraLinkWhatsappPedidoSaiuEntrega($pedido);

                        if ($linkWhatsapp !== null) {
                            $mensagemSucesso .= ' <a href="' . $linkWhatsapp . '" target="_blank" rel="noopener">Clique aqui para enviar a atualizacao por WhatsApp.</a>';
                        } else {
                            $this->enviaEmailPedidoSaiuEntrega($pedido);
                            $mensagemSucesso .= ' Telefone do cliente invalido para WhatsApp. A atualizacao foi enviada por e-mail.';
                        }
                    } else {
                        $this->enviaEmailPedidoSaiuEntrega($pedido);
                    }
                }
                if ($pedido->situacao == 2 && $situacaoAnteriorPedido != 2) {

                    if (($pedido->canal_acompanhamento ?? 'email') === 'whatsapp') {
                        $linkWhatsapp = $this->geraLinkWhatsappPedidoFoiEntregue($pedido);

                        if ($linkWhatsapp !== null) {
                            $mensagemSucesso .= ' <a href="' . $linkWhatsapp . '" target="_blank" rel="noopener">Clique aqui para enviar a atualizacao por WhatsApp.</a>';
                        } else {
                            $this->enviaEmailPedidoFoiEntregue($pedido);
                            $mensagemSucesso .= ' Telefone do cliente invalido para WhatsApp. A atualizacao foi enviada por e-mail.';
                        }
                    } else {
                        $this->enviaEmailPedidoFoiEntregue($pedido);
                    }
                }
                if ($pedido->situacao == 3) {
                    if ($situacaoAnteriorPedido == 1) {
                        session()->setFlashdata('info', 'O pedido foi cancelado após ter saído para entrega. Por favor, entre em contato com o entregador para informar sobre o cancelamento e evitar transtornos.');
                    }

                    if (($pedido->canal_acompanhamento ?? 'email') === 'whatsapp') {
                        $linkWhatsapp = $this->geraLinkWhatsappPedidoFoiCancelado($pedido);

                        if ($linkWhatsapp !== null) {
                            $mensagemSucesso .= ' <a href="' . $linkWhatsapp . '" target="_blank" rel="noopener">Clique aqui para enviar a atualizacao por WhatsApp.</a>';
                        } else {
                            $this->enviaEmailPedidoFoiCancelado($pedido);
                            $mensagemSucesso .= ' Telefone do cliente invalido para WhatsApp. A atualizacao foi enviada por e-mail.';
                        }
                    } else {
                        $this->enviaEmailPedidoFoiCancelado($pedido);
                    }
                }

                return $redirectShow->with('sucesso', $mensagemSucesso);
            } else {
                return $redirectShow->with('errors_model', $this->pedidoModel->errors())->with('atencao', 'Por favor, verifique os erros abaixo e tente novamente.');
            }
        } else {
            return $redirectShow;
        }
    }
    public function excluir($codigoPedido = null)
    {
        $pedido = $this->pedidoModel->buscaPedidoOu404($codigoPedido);
        if ($pedido->deletado_em != null) {
            return redirect()->back()->with('info', 'O pedido já está excluído.');
        }
        if ($pedido->situacao < 2) {
            return redirect()->back()->with('info', 'Não é possível excluir um pedido que ainda não foi entregue ou cancelado.');
        }
        if ($this->request->getMethod() === 'POST') {
            $this->pedidoModel->delete($pedido->id);
            return redirect()->to(site_url('admin/pedidos'))->with('sucesso', 'Pedido excluído com sucesso.');
        }
        $data = [
            'titulo' => 'Excluindo pedido: ' . $pedido->codigo,
            'pedido' => $pedido,
        ];
        return view('Admin/Pedidos/excluir', $data);
    }
    public function desfazerExclusao($codigoPedido = null)
    {
        if (!$codigoPedido || !$pedido = $this->pedidoModel->withDeleted(true)->where('codigo', $codigoPedido)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pedido não encontrado');
        }

        if ($pedido->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas pedidos excluídos podem ser restaurados.');
        }

        if ($this->pedidoModel->desfazerExclusao((int) $pedido->id)) {
            return redirect()->back()->with('sucesso', "Exclusão do pedido $pedido->codigo desfeita com sucesso!");
        }

        return redirect()->back()
            ->with('errors_model', $this->pedidoModel->errors())
            ->with('atencao', 'Por favor, verifique os erros abaixo!')
            ->withInput();
    }
    private function geraLinkWhatsappPedidoSaiuEntrega(object $pedido): ?string
    {
        $telefone = $this->resolveTelefoneClienteWhatsapp($pedido);

        if ($telefone === null) {
            return null;
        }

        $mensagem = $this->montaMensagemWhatsappPedidoSaiuEntrega($pedido);

        return 'https://wa.me/' . $telefone . '?text=' . rawurlencode($mensagem);
    }
    private function montaMensagemWhatsappPedidoSaiuEntrega(object $pedido): string
    {
        $nomeCliente = trim((string) ($pedido->nome ?? ''));
        $nomeEntregador = trim((string) ($pedido->entregador->nome ?? ''));
        $observacoes = trim((string) ($pedido->observacoes ?? ''));
        $valorPedido = number_format((float) ($pedido->valor_pedido ?? 0), 2, ',', '.');

        return 'Pedido ' . $pedido->codigo . ' saiu para entrega.' . PHP_EOL . PHP_EOL
            . 'Ola ' . $nomeCliente . ', seu pedido saiu para entrega.' . PHP_EOL
            . 'Forma de pagamento: ' . $pedido->forma_pagamento . PHP_EOL
            . 'Valor do pedido: R$ ' . $valorPedido . PHP_EOL
            . 'Endereco de entrega: ' . $pedido->endereco_entrega . PHP_EOL
            . 'Observacoes: ' . ($observacoes !== '' ? $observacoes : 'Nenhuma') . PHP_EOL
            . 'Entregador: ' . $nomeEntregador . PHP_EOL . PHP_EOL
            . 'Acompanhe seus pedidos em: ' . site_url('conta');
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
    private function enviaEmailPedidoFoiEntregue(object $pedido)
    {
        $email = service('email');

        $email->setFrom('no-reply@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($pedido->email);
        $email->setSubject("Pedido {$pedido->codigo} foi entregue");

        $mensagem = view('Admin/Pedidos/pedido_foi_entregue_email', ['pedido' => $pedido]);

        $email->setMessage($mensagem);
        $email->send();
    }
    private function enviaEmailPedidoFoiCancelado(object $pedido)
    {
        $email = service('email');

        $email->setFrom('no-reply@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($pedido->email);
        $email->setSubject("Pedido {$pedido->codigo} foi cancelado");

        $mensagem = view('Admin/Pedidos/pedido_foi_cancelado_email', ['pedido' => $pedido]);

        $email->setMessage($mensagem);
        $email->send();
    }
    private function geraLinkWhatsappPedidoFoiEntregue(object $pedido): ?string
    {
        $telefone = $this->resolveTelefoneClienteWhatsapp($pedido);

        if ($telefone === null) {
            return null;
        }

        $mensagem = $this->montaMensagemWhatsappPedidoFoiEntregue($pedido);

        return 'https://wa.me/' . $telefone . '?text=' . rawurlencode($mensagem);
    }
    private function geraLinkWhatsappPedidoFoiCancelado(object $pedido): ?string
    {
        $telefone = $this->resolveTelefoneClienteWhatsapp($pedido);

        if ($telefone === null) {
            return null;
        }

        $mensagem = $this->montaMensagemWhatsappPedidoFoiCancelado($pedido);

        return 'https://wa.me/' . $telefone . '?text=' . rawurlencode($mensagem);
    }
    private function montaMensagemWhatsappPedidoFoiEntregue(object $pedido): string
    {
        $nomeCliente = trim((string) ($pedido->nome ?? ''));
        $valorPedido = number_format((float) ($pedido->valor_pedido ?? 0), 2, ',', '.');

        return 'Pedido ' . $pedido->codigo . ' foi entregue.' . PHP_EOL . PHP_EOL
            . 'Ola ' . $nomeCliente . ', seu pedido foi entregue com sucesso.' . PHP_EOL
            . 'Esperamos que tenha gostado do seu pedido e que aproveite ao máximo a experiencia de sabor que somente *Gula Lanches* pode proporcionar!';
    }
    private function montaMensagemWhatsappPedidoFoiCancelado(object $pedido): string
    {
        $nomeCliente = trim((string) ($pedido->nome ?? ''));

        return 'Pedido ' . $pedido->codigo . ' foi cancelado.' . PHP_EOL . PHP_EOL
            . 'Ola ' . $nomeCliente . ', seu pedido foi cancelado.' . PHP_EOL
            . 'Lamentamos que isso tenha acontecido e estamos aqui para ajudar.';
    }

    private function resolveTelefoneClienteWhatsapp(object $pedido): ?string
    {
        $telefoneCadastro = '';

        if (!empty($pedido->usuario_id)) {
            $usuario = $this->usuarioModel->withDeleted(true)->find((int) $pedido->usuario_id);
            $telefoneCadastro = (string) ($usuario->telefone ?? '');
        }

        $telefone = preg_replace('/\D/', '', $telefoneCadastro !== '' ? $telefoneCadastro : (string) ($pedido->telefone ?? ''));

        if ($telefone === '') {
            return null;
        }

        if (strlen($telefone) === 11) {
            $telefone = '55' . $telefone;
        }

        if (strlen($telefone) < 12) {
            return null;
        }

        return $telefone;
    }

    private function insereProdutosDoPedido(object $pedido)
    {
        $pedidoProdutoModel = new \App\Models\PedidoProdutoModel();

        $pedidoProdutoModel->where('pedido_id', $pedido->id)->delete();

        if (method_exists($pedido, 'getProdutosPedido')) {
            $produtos = $pedido->getProdutosPedido();
        } elseif (is_array($pedido->produtos ?? null)) {
            $produtos = $pedido->produtos;
        } else {
            $produtos = @unserialize((string) ($pedido->produtos ?? ''));
        }

        if (!is_array($produtos) || $produtos === []) {
            return;
        }

        $produtosDoPedido = [];
        foreach ($produtos as $produto) {
            if (!isset($produto['nome'], $produto['quantidade'])) {
                continue;
            }

            array_push($produtosDoPedido, [
                'pedido_id' => $pedido->id,
                'produto' => $produto['nome'],
                'quantidade' => (int) $produto['quantidade'],
            ]);
        }

        if ($produtosDoPedido !== []) {
            $pedidoProdutoModel->insertBatch($produtosDoPedido);
        }
    }
}
