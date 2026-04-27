<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Checkout extends BaseController
{
    private $usuario;
    private $formaPagamentoModel;
    private $bairroModel;
    private $pedidoModel;

    public function __construct()
    {
        $this->usuario = service('autenticacao')->pegaUsuarioLogado();
        $this->formaPagamentoModel = new \App\Models\FormaPagamentoModel();
        $this->bairroModel = new \App\Models\BairroModel();
        $this->pedidoModel = new \App\Models\PedidoModel();
    }

    public function index()
    {
        if (!session()->has('carrinho') || count(session()->get('carrinho')) < 1) {
            return redirect()->to(site_url('carrinho'));
        }

        $data = [
            'titulo' => 'Finalizar Pedido',
            'carrinho' => session()->get('carrinho'),
            'formas' => $this->formaPagamentoModel->where('ativo', true)->findAll(),
        ];

        return view('Checkout/index', $data);
    }

    private function somaValorProdutosCarrinho()
    {
        $carrinho = session()->get('carrinho') ?? [];
        $total = 0;

        foreach ($carrinho as $produto) {
            $total += ((float) $produto['preco']) * ((int) $produto['quantidade']);
        }

        return $total;
    }

    public function consultaBairro()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to(site_url('/'));
        }

        $retorno = [];

        $bairroInformado = trim((string) $this->request->getGet('bairro_slug'));
        if ($bairroInformado === '') {
            $bairroInformado = trim((string) $this->request->getGet('bairro'));
        }

        $bairroInformado = trim((string) preg_replace('/[^\p{L}\p{N}\s]/u', '', $bairroInformado));
        if ($bairroInformado === '') {
            $retorno['erro'] = '<span class="text-danger small">Informe o nome do bairro</span>';
            return $this->response->setJSON($retorno);
        }

        $bairroInformadoSlug = mb_url_title($bairroInformado, '-', true);

        $bairroPadrao = $this->bairroModel
            ->select(['slug', 'nome', 'valor_entrega'])
            ->where('ativo', true)
            ->where('slug !=', 'morro-verde')
            ->orderBy('id', 'ASC')
            ->first();

        if ($bairroPadrao === null) {
            $bairroPadrao = $this->bairroModel
                ->select(['slug', 'nome', 'valor_entrega'])
                ->where('ativo', true)
                ->orderBy('id', 'ASC')
                ->first();
        }

        if ($bairroPadrao === null) {
            $retorno['erro'] = '<span class="text-danger small">Nao foi possivel calcular a taxa de entrega no momento.</span>';
            return $this->response->setJSON($retorno);
        }

        $bairroMorroVerde = $this->bairroModel
            ->select(['slug', 'nome', 'valor_entrega'])
            ->where('slug', 'morro-verde')
            ->where('ativo', true)
            ->first();

        $bairroSelecionado = $bairroPadrao;
        $nomeBairroExibicao = $bairroInformado;

        if ($bairroInformadoSlug === 'morro-verde' && $bairroMorroVerde !== null) {
            $bairroSelecionado = $bairroMorroVerde;
            $nomeBairroExibicao = (string) $bairroMorroVerde->nome;
        }

        // Usar o slug real do bairro encontrado no banco, não gerar um novo
        $retorno['bairro_slug'] = (string) $bairroSelecionado->slug;
        $retorno['nome_bairro'] = (string) $nomeBairroExibicao;
        $retorno['valor_entrega'] = 'R$ ' . esc(number_format((float) $bairroSelecionado->valor_entrega, 2, ',', '.'));
        $retorno['bairro'] = '<span class="text-success small">Valor de entrega para o bairro ' . esc($nomeBairroExibicao) . ': ' . $retorno['valor_entrega'] . '</span>';

        $total = $this->somaValorProdutosCarrinho();
        $total += (float) $bairroSelecionado->valor_entrega;
        session()->set('endereco_entrega', (string) $nomeBairroExibicao);
        session()->set('valor_entrega', (float) $bairroSelecionado->valor_entrega);
        $retorno['total'] = 'R$ ' . esc(number_format($total, 2, ',', '.'));

        return $this->response->setJSON($retorno);
    }

    public function consultaCep()
    {
        return $this->consultaBairro();
    }
    public function processar()
    {
        if ($this->request->is('post')) {
            if (!$this->empresaAbertaAgora()) {
                return redirect()->back()
                    ->with('info', 'Não é possível fazer pedidos fora do horário de funcionamento.');
            }

            $checkoutPost = (array) $this->request->getPost('checkout');

            $bairroSlug = trim((string) ($checkoutPost['bairro_slug'] ?? ''));
            if ($bairroSlug === '') {
                $bairroSlug = trim((string) $this->request->getPost('bairro_slug'));
            }

            if ($bairroSlug !== '') {
                $checkoutPost['bairro_slug'] = mb_url_title($bairroSlug, '-', true);
            }

            $bairroDigitado = trim((string) $this->request->getPost('bairro_slug'));
            if ($bairroDigitado === '') {
                $bairroDigitado = trim((string) session()->get('endereco_entrega'));
            }
            if ($bairroDigitado === '') {
                $bairroDigitado = trim((string) ($checkoutPost['bairro_slug'] ?? ''));
            }

            $dadosValidacao = ['checkout' => $checkoutPost];
            $validacao = service('validation');
            $validacao->setRules([
                'checkout.rua' => ['label' => 'Endereço', 'rules' => 'required|min_length[3]|max_length[50]'],
                'checkout.numero' => ['label' => 'Número', 'rules' => 'required|max_length[30]'],
                'checkout.referencia' => ['label' => 'Ponto de Referência', 'rules' => 'max_length[50]'],
                'checkout.forma_id' => ['label' => 'Forma de Pagamento', 'rules' => 'required|integer'],
                'checkout.bairro_slug' => ['label' => 'Bairro de Entrega', 'rules' => 'required|string|max_length[50]'],
            ]);
            if (!$validacao->run($dadosValidacao)) {
                session()->remove('endereco_entrega');
                return redirect()->back()
                    ->with('errors_model', $validacao->getErrors())
                    ->with('atencao', 'Corrija os erros abaixo e tente novamente.');
            }
            $forma = $this->formaPagamentoModel->where('id', $checkoutPost['forma_id'])->where('ativo', true)->first();
            if ($forma === null) {
                session()->remove('endereco_entrega');
                return redirect()->back()
                    ->with('errors_model', ['checkout.forma_id' => 'Forma de pagamento inválida.'])
                    ->with('atencao', 'Corrija os erros abaixo e tente novamente.');
            }

            $pedido = new \App\Entities\Pedido();
            $pedido->usuario_id = $this->usuario->id;
            $pedido->codigo = $this->pedidoModel->geraCodigoPedido();
            $pedido->forma_pagamento = $forma->nome;
            $pedido->produtos = serialize(session()->get('carrinho'));
            $pedido->valor_produtos = number_format($this->somaValorProdutosCarrinho(), 2, '.', '');
            $valorEntrega = (float) session()->get('valor_entrega');
            if ($valorEntrega <= 0 && !empty($checkoutPost['bairro_slug'])) {
                $bairro = $this->bairroModel
                    ->select(['valor_entrega'])
                    ->where('slug', $checkoutPost['bairro_slug'])
                    ->where('ativo', true)
                    ->first();

                if ($bairro !== null) {
                    $valorEntrega = (float) $bairro->valor_entrega;
                }
            }

            $pedido->valor_entrega = number_format($valorEntrega, 2, '.', '');
            $pedido->valor_pedido = number_format((float) ($pedido->valor_produtos + $pedido->valor_entrega), 2, '.', '');
            $pedido->endereco_entrega = $bairroDigitado . ' - Número ' . $checkoutPost['numero'];
            $canalAcompanhamento = 'email';
            $pedido->canal_acompanhamento = $canalAcompanhamento;

            $observacoesBase = 'Ponto de referência: ' . $checkoutPost['referencia'] . ' - Número: ' . $checkoutPost['numero'];
            if ($forma->id == 1) {
                if (isset($checkoutPost['sem_troco'])) {
                    $pedido->observacoes = $observacoesBase . ' - Sem necessidade de troco.';
                } else {
                    $trocoParaInformado = trim((string) ($checkoutPost['troco_para'] ?? ''));

                    if ($trocoParaInformado === '') {
                        return redirect()->back()
                            ->withInput()
                            ->with('errors_model', ['checkout.troco_para' => 'Informe um valor para o campo "Troco para" ou marque a opção "Não quero troco".'])
                            ->with('atencao', 'Corrija os erros abaixo e tente novamente.');
                    }

                    $trocoParaNormalizado = str_replace('.', '', $trocoParaInformado);
                    $trocoParaNormalizado = str_replace(',', '.', $trocoParaNormalizado);

                    if (!is_numeric($trocoParaNormalizado) || (float) $trocoParaNormalizado <= 0) {
                        return redirect()->back()
                            ->withInput()
                            ->with('errors_model', ['checkout.troco_para' => 'Informe um valor válido para o campo "Troco para".'])
                            ->with('atencao', 'Corrija os erros abaixo e tente novamente.');
                    }

                    $valorTrocoPara = (float) $trocoParaNormalizado;
                    $valorTotalPedido = (float) $pedido->valor_pedido;

                    if ($valorTrocoPara <= $valorTotalPedido) {
                        return redirect()->back()
                            ->withInput()
                            ->with('errors_model', ['checkout.troco_para' => 'O valor informado em "Troco para" deve ser maior que o total do pedido.'])
                            ->with('atencao', 'Corrija os erros abaixo e tente novamente.');
                    }

                    $pedido->observacoes = $observacoesBase . ' - Troco para R$ ' . number_format($valorTrocoPara, 2, ',', '.');
                }
            } else {
                $pedido->observacoes = $observacoesBase;
            }
            if (!$this->pedidoModel->save($pedido)) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors_model', $this->pedidoModel->errors())
                    ->with('atencao', 'Não foi possível finalizar o pedido. Tente novamente.');
            }

            $pedidoSalvo = $this->pedidoModel->where('codigo', $pedido->codigo)->first();
            if ($pedidoSalvo !== null) {
                $this->insereProdutosDoPedido($pedidoSalvo);
            }

            session()->remove('carrinho');
            session()->remove('endereco_entrega');
            $pedido->usuario = $this->usuario;

            $mensagemAcompanhamento = $this->montaMensagemAcompanhamento($pedido);
            $whatsappLink = $this->geraLinkWhatsappAcompanhamento($mensagemAcompanhamento);

            session()->setFlashdata('canal_acompanhamento', $canalAcompanhamento);
            session()->setFlashdata('whatsapp_link', $whatsappLink);

            return redirect()->to(site_url('checkout/concluido/' . $pedido->codigo)); // - /sucesso/ . $pedido->codigo);
        } else {
            return redirect()->back();
        }
    }
    public function concluido(?string $codigo = null)
    {
        if ($codigo === null) {
            return redirect()->to(site_url('carrinho'));
        }

        $pedido = $this->pedidoModel
            ->where('codigo', $codigo)
            ->first();

        $situacaoPedido = is_object($pedido) && isset($pedido->situacao)
            ? (int) $pedido->situacao
            : 0;

        $data = [
            'pedido' => $pedido,
            'titulo' => 'Pedido realizado com sucesso',
            'codigo_pedido' => $codigo,
            'situacao_pedido' => $situacaoPedido,
            'canal_acompanhamento' => $pedido->canal_acompanhamento ?? session()->getFlashdata('canal_acompanhamento') ?? 'email',
            'whatsapp_link' => session()->getFlashdata('whatsapp_link') ?? $this->geraLinkWhatsappAcompanhamento($this->montaMensagemAcompanhamentoCodigo($codigo)),
            'mensagem_acompanhamento' => $this->montaMensagemAcompanhamentoCodigo($codigo),
        ];

        return view('Checkout/concluido', $data);
    }

    public function atualizarCanalAcompanhamento(string $codigo)
    {
        if (!$this->request->is('post')) {
            return redirect()->to(site_url('checkout/concluido/' . $codigo));
        }

        $canal = trim((string) $this->request->getPost('canal_acompanhamento'));

        if (!in_array($canal, ['email', 'whatsapp'], true)) {
            return redirect()->back()->with('atencao', 'Canal de acompanhamento inválido.');
        }

        $pedido = $this->pedidoModel
            ->where('codigo', $codigo)
            ->where('usuario_id', $this->usuario->id)
            ->first();

        if ($pedido === null) {
            return redirect()->to(site_url('conta'))->with('atencao', 'Pedido não encontrado.');
        }

        $pedido->canal_acompanhamento = $canal;

        if (!$this->pedidoModel->save($pedido)) {
            return redirect()->back()
                ->with('errors_model', $this->pedidoModel->errors())
                ->with('atencao', 'Não foi possível atualizar o canal de acompanhamento.');
        }

        if ($canal === 'email') {
            $pedido->usuario = $this->usuario;
            $this->enviaEmailPedidoRealizado($pedido);

            return redirect()->to(site_url('checkout/concluido/' . $codigo))
                ->with('sucesso', 'Canal de acompanhamento atualizado para E-mail. Mensagem enviada com sucesso.');
        }

        $mensagemAcompanhamento = $this->montaMensagemAcompanhamento($pedido);
        $whatsappLink = $this->geraLinkWhatsappAcompanhamento($mensagemAcompanhamento);

        return redirect()->to($whatsappLink);
    }

    private function montaMensagemAcompanhamento(object|string $pedido): string
    {
        if (!is_object($pedido)) {
            return 'Pedido ' . (string) $pedido . ' realizado com sucesso.' . PHP_EOL
                . 'Entre em sua conta para acompanhar o status do seu pedido: ' . site_url('conta');
        }

        $codigo = (string) ($pedido->codigo ?? '');
        $nome = trim((string) (($pedido->usuario->nome ?? null) ?? ($this->usuario->nome ?? '')));
        $produtos = method_exists($pedido, 'getProdutosPedido') ? $pedido->getProdutosPedido() : [];
        $linhasProdutos = [];

        foreach ($produtos as $produto) {
            if (!is_array($produto) || !isset($produto['nome'], $produto['quantidade'])) {
                continue;
            }

            $nomeProduto = trim((string) $produto['nome']);
            if ($nomeProduto === '') {
                continue;
            }

            $quantidade = max(1, (int) $produto['quantidade']);
            $preco = isset($produto['preco']) ? number_format((float) $produto['preco'], 2, ',', '.') : '0,00';

            $linhasProdutos[] = '- ' . $nomeProduto . ' | Quantidade: ' . $quantidade . ' | Preco: R$ ' . $preco;
        }

        $resumoProdutos = $linhasProdutos !== []
            ? implode(PHP_EOL, $linhasProdutos)
            : '- Nenhum produto informado';

        $valorProdutos = number_format((float) ($pedido->valor_produtos ?? 0), 2, ',', '.');
        $valorEntrega = number_format((float) ($pedido->valor_entrega ?? 0), 2, ',', '.');
        $valorPedido = number_format((float) ($pedido->valor_pedido ?? 0), 2, ',', '.');
        $formaPagamento = trim((string) ($pedido->forma_pagamento ?? '')) ?: 'Nao informado';
        $enderecoEntrega = trim((string) ($pedido->endereco_entrega ?? '')) ?: 'Nao informado';
        $observacoes = trim((string) ($pedido->observacoes ?? '')) ?: 'Nenhuma';

        return 'Pedido ' . $codigo . ' realizado com sucesso!' . PHP_EOL . PHP_EOL
            . 'Olá ' . $nome . ', recebemos seu pedido ' . $codigo . ' e estamos processando-o.' . PHP_EOL . PHP_EOL
            . 'Resumo do pedido:' . PHP_EOL
            . $resumoProdutos . PHP_EOL . PHP_EOL
            . 'Total de produtos: R$ ' . $valorProdutos . PHP_EOL
            . 'Taxa de entrega: R$ ' . $valorEntrega . PHP_EOL
            . 'Valor final: R$ ' . $valorPedido . PHP_EOL
            . 'Forma de pagamento: ' . $formaPagamento . PHP_EOL
            . 'Endereco de entrega: ' . $enderecoEntrega . PHP_EOL
            . 'Observacoes: ' . $observacoes . PHP_EOL . PHP_EOL
            . 'Entre em sua conta para acompanhar o status do seu pedido: ' . site_url('conta');
    }

    private function montaMensagemAcompanhamentoCodigo(string $codigo): string
    {
        $pedido = $this->pedidoModel->where('codigo', $codigo)->first();

        if ($pedido !== null) {
            $pedido->usuario = $this->usuario;
            return $this->montaMensagemAcompanhamento($pedido);
        }

        $pedidoFallback = new \stdClass();
        $pedidoFallback->codigo = $codigo;
        $pedidoFallback->usuario = $this->usuario;

        return $this->montaMensagemAcompanhamento($pedidoFallback);
    }

    private function geraLinkWhatsappAcompanhamento(string $mensagem): string
    {
        $numero = preg_replace('/\D/', '', '5535991052828');

        return 'https://wa.me/' . $numero . '?text=' . rawurlencode($mensagem);
    }
    private function enviaEmailPedidoRealizado(object $pedido)
    {

        $email = service('email');

        $email->setFrom('no-reply@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($this->usuario->email);
        $email->setSubject('Pedido ' . $pedido->codigo . ' - Food Delivery - Realizado com Sucesso');
        $mensagem = view('Checkout/pedido_email', [
            'pedido' => $pedido,
            'mensagem_acompanhamento' => $this->montaMensagemAcompanhamento($pedido),
        ]);
        $email->setMessage($mensagem);
        $email->send();
    }

    private function insereProdutosDoPedido(object $pedido): void
    {
        if (empty($pedido->id)) {
            return;
        }

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

            $produtosDoPedido[] = [
                'pedido_id' => $pedido->id,
                'produto' => $produto['nome'],
                'quantidade' => (int) $produto['quantidade'],
            ];
        }

        if ($produtosDoPedido !== []) {
            $pedidoProdutoModel->insertBatch($produtosDoPedido);
        }
    }

    private function empresaAbertaAgora(): bool
    {
        helper('empresa');
        $expedienteHoje = expedienteHoje();

        if ($expedienteHoje === null) {
            return false;
        }

        if (!isset($expedienteHoje->situacao) || (int) $expedienteHoje->situacao !== 1) {
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
