<?php

namespace App\Controllers;

use App\Controllers\BaseController;
class Carrinho extends BaseController
{
    private $validacao;
    private $produtoEspecificacaoModel;
    private $extraModel;
    private $produtoModel;
    public function __construct()
    {
        $this->validacao = service('validation');
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
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
            $especificacaoId = $produtoPost['especificacao_id'] ?? null;
            $especificacaoProduto = $this->produtoEspecificacaoModel->where('id', $especificacaoId)->first();
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
            $produto = $this->produtoModel->where('slug', $produtoPost['slug'])->first();
            if ($produto === null || $produto->ativo == false) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-PROD-3003</strong>');//FRAUDE NO FORM na chave produtoPost[slug]
            }

            dd($especificacaoProduto);
        }
    }
}
