<?php

namespace App\Controllers;

use App\Controllers\BaseController;
class Carrinho extends BaseController
{
    private $validacao;
    private $produtoEspecificacaoModel;
    private $extraModel;
    private $produtoModel;
    private $acao;
    public function __construct()
    {
        $this->validacao = service('validation');
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoModel = new \App\Models\ProdutoModel();

        $this->acao = service('router')->methodName();
    }
    public function index()
    {
        //
    }
    public function adicionar()
    {
        $validation = service('validation');
        if ($this->request->getMethod() === 'POST') {

            $produtoPost = $this->request->getPost('produto') ?? [];

            $validation->setRules([
                'produto.slug' => ['label' => 'Produto', 'rules' => 'required|max_length[30]|string'],
                'produto.especificacao_id' => ['label' => 'Valor do produto', 'rules' => 'required|greater_than[0]|integer'],
                'produto.preco' => ['label' => 'Valor do produto', 'rules' => 'required|greater_than[0]'],
                'produto.quantidade' => ['label' => 'Quantidade', 'rules' => 'required|greater_than[0]'],
                'produto.extra_id' => ['label' => 'Extra', 'rules' => 'permit_empty|greater_than[0]|integer'],
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->with('errors_model', $this->validacao->geterrors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }

            /** Validamos a existência da especificacao_id */
            $especificacaoProduto = $this->produtoEspecificacaoModel
                ->join('medidas', 'medidas.id = produtos_especificacoes.medida_id')
                ->where('produtos_especificacoes.id', $produtoPost['especificacao_id'])
                ->first();

            if ($especificacaoProduto === null) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-PROD-1001</strong>');//FRAUDE NO FORM
            }

            $extraId = $produtoPost['extra_id'] ?? null;
            if ($extraId !== null && $extraId !== '') {
                $extra = $this->extraModel->where('id', $extraId)->first();
                if ($extra === null) {
                    return redirect()->back()
                        ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-PROD-2002</strong>');//FRAUDE NO FORM... chave produtoPost[extra_id] 
                }
            }

            $produto = $this->produtoModel->select(['id', 'nome', 'slug', 'ativo'])->where('slug', $produtoPost['slug'])->first()->toArray();

            if ($produto === null || $produto['ativo'] == false) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-PROD-3003</strong>');//FRAUDE NO FORM na chave produtoPost[slug]
            }

            $produto['slug'] = mb_url_title($especificacaoProduto->nome . '-' . $produto['slug'] . '-' . (isset($extra) ? 'com extra-' . $extra->nome : ''), '-', true);

            $produto['nome'] = $produto['nome'] . ' - ' . $especificacaoProduto->nome . (isset($extra) ? ' Com extra ' . $extra->nome : '');

            $preco = $especificacaoProduto->preco + (isset($extra) ? $extra->preco : 0);
            $produto['preco'] = number_format($preco, 2);
            $produto['quantidade'] = (int) $produtoPost['quantidade'];
            $produto['tamanho'] = $especificacaoProduto->nome;

            unset($produto['ativo']);

            if (session()->has('carrinho')) {

                $produtos = session()->get('carrinho');
                $produtosSlugs = array_column($produtos, 'slug');

                if (in_array($produto['slug'], $produtosSlugs)) {
                    //Já existe no arrinho... imcrementamos a 

                    $produtos = $this->atualizaProduto($this->acao, $produto['slug'], $produto['quantidade'], $produtos);
                    session()->set('carrinho', $produtos);
                } else {
                    //Não existe no carrinho, adicionamos
                    session()->push('carrinho', [$produto]);
                }

            } else {
                $produtos[] = $produto;
                session()->set('carrinho', $produtos);
            }
            return redirect()->back()
                ->with('sucesso', 'Produto adicionado ao carrinho com sucesso!');

        } else {
            return redirect()->back();
        }
    }

    private function atualizaProduto(string $acao, string $slug, int $quantidade, array $produtos)
    {
        $produtos = array_map(function ($linha) use ($acao, $slug, $quantidade) {
            if ($linha['slug'] === $slug) {
                if ($acao === 'adicionar') {
                    $linha['quantidade'] += $quantidade;
                } elseif ($acao === 'atualizar') {
                    $linha['quantidade'] = $quantidade;
                }
            }
            return $linha;
        }, $produtos);

        return $produtos;
    }
}