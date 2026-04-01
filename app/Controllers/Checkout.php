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

                    $pedido->observacoes = $observacoesBase . ' - Troco para R$ ' . number_format((float) $trocoParaNormalizado, 2, ',', '.');
                }
            } else {
                $pedido->observacoes = $observacoesBase;
            }
            $this->pedidoModel->save($pedido);
            $pedido->usuario = $this->usuario;
            $this->enviaEmailPedidoRealizado($pedido);
        } else {
            return redirect()->back();
        }
    }
    private function enviaEmailPedidoRealizado(object $pedido)
    {

        $email = service('email');

        $email->setFrom('no-reply@fooddelivery.com.br', 'Food Delivery');
        $email->setTo($this->usuario->email);
        $email->setSubject('Pedido ' . $pedido->codigo . ' - Food Delivery - Realizado com Sucesso');
        $mensagem = view('Checkout/pedido_email', ['pedido' => $pedido]);
        $email->setMessage($mensagem);
        $email->send();
    }
}
