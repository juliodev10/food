<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Carrinho extends BaseController
{
    private $validacao;
    private $produtoEspecificacaoModel;
    private $extraModel;
    private $produtoModel;
    private $medidaModel;
    private $bairroModel;
    private $acao;
    public function __construct()
    {
        $this->validacao = service('validation');
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->medidaModel = new \App\Models\MedidaModel();
        $this->bairroModel = new \App\Models\BairroModel();

        $this->acao = service('router')->methodName();
    }
    public function index()
    {
        $data = [
            'titulo' => 'Meu carrinho de compras',
        ];

        if (session()->has('carrinho') && count(session()->get('carrinho')) > 0) {
            $carrinho = json_decode(json_encode(session()->get('carrinho')), false);

            $produtosIds = [];
            foreach ($carrinho as $item) {
                if (!empty($item->id)) {
                    $produtosIds[] = (int) $item->id;
                }
            }

            if (!empty($produtosIds)) {
                $produtosComCategoria = $this->produtoModel
                    ->select(['produtos.id', 'categorias.slug AS categoria_slug'])
                    ->join('categorias', 'categorias.id = produtos.categoria_id')
                    ->whereIn('produtos.id', array_unique($produtosIds))
                    ->findAll();

                $categoriasPorProdutoId = [];
                foreach ($produtosComCategoria as $produtoCategoria) {
                    $categoriasPorProdutoId[$produtoCategoria->id] = $produtoCategoria->categoria_slug;
                }

                foreach ($carrinho as $item) {
                    $itemId = isset($item->id) ? (int) $item->id : null;
                    if ($itemId !== null && isset($categoriasPorProdutoId[$itemId])) {
                        $item->categoria_slug = $categoriasPorProdutoId[$itemId];
                    }

                    $categoriaEhLanche = $this->categoriaEhLanche($item->categoria_slug ?? null);
                    if (!$categoriaEhLanche) {
                        if (isset($item->nome)) {
                            $item->nome = preg_replace('/\s*-\s*inteiro\b/iu', '', $item->nome);
                        }
                    }
                }
            }

            $data['carrinho'] = $carrinho;
        }

        return view('Carrinho/index', $data);
    }
    public function adicionar()
    {
        $validation = service('validation');
        if ($this->request->getMethod() === 'POST') {

            $valorProduto = $this->request->getPost('produto') ?? [];

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
                ->where('produtos_especificacoes.id', $valorProduto['especificacao_id'])
                ->first();

            if ($especificacaoProduto === null) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-PROD-1001</strong>'); //FRAUDE NO FORM
            }

            $extraId = $valorProduto['extra_id'] ?? null;
            if ($extraId !== null && $extraId !== '') {
                $extra = $this->extraModel->where('id', $extraId)->first();
                if ($extra === null) {
                    return redirect()->back()
                        ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-PROD-2002</strong>'); //FRAUDE NO FORM... chave valorProduto[extra_id] 
                }
            }

            $produto = $this->produtoModel
                ->select(['produtos.id', 'produtos.nome', 'produtos.slug', 'produtos.ativo', 'categorias.slug AS categoria_slug'])
                ->join('categorias', 'categorias.id = produtos.categoria_id')
                ->where('produtos.slug', $valorProduto['slug'])
                ->first();

            if ($produto === null || $produto->ativo == false) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-PROD-3003</strong>'); //FRAUDE NO FORM na chave valorProduto[slug]
            }
            $produto = $produto->toArray();

            $medidaEhInteiro = mb_strtolower($especificacaoProduto->nome) === 'inteiro';
            $deveExibirInteiro = !$medidaEhInteiro || $this->categoriaEhLanche($produto['categoria_slug'] ?? null);

            $produto['slug'] = mb_url_title($especificacaoProduto->nome . '-' . $produto['slug'] . '-' . (isset($extra) ? 'com extra-' . $extra->nome : ''), '-', true);

            $produto['nome'] = $produto['nome']
                . ($deveExibirInteiro ? ' - ' . $especificacaoProduto->nome : '')
                . (isset($extra) ? ' Com extra ' . $extra->nome : '');

            $preco = $especificacaoProduto->preco + (isset($extra) ? $extra->preco : 0);
            $produto['preco'] = number_format($preco, 2);
            $produto['quantidade'] = (int) $valorProduto['quantidade'];
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
            return redirect()->to(site_url('carrinho'))
                ->with('sucesso', 'Produto adicionado ao carrinho com sucesso!');
        } else {
            return redirect()->back();
        }
    }
    public function especial()
    {
        if ($this->request->getMethod() === 'POST') {
            $valorProduto = $this->request->getPost();
            $this->validacao->setRules([
                'primeira_metade' => ['label' => 'Primeiro_produto', 'rules' => 'required|greater_than[0]|integer'],
                'segunda_metade' => ['label' => 'Segundo_produto', 'rules' => 'required|greater_than[0]|integer'],
            ]);

            if (!$this->validacao->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->with('errors_model', $this->validacao->geterrors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }

            $primeiroProduto = $this->produtoModel->select(['id', 'nome', 'slug'])->where('id', $valorProduto['primeira_metade'])->first();
            if ($primeiroProduto === null) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível processar a solicitação. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-CUSTOM-1001</strong>'); //FRAUDE NO FORM na chave valorProduto[primeira_metade]
            }

            $segundoProduto = $this->produtoModel->select(['id', 'nome', 'slug'])->where('id', $valorProduto['segunda_metade'])->first();
            if ($segundoProduto === null) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível processar a solicitação. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-CUSTOM-2002</strong>'); //FRAUDE NO FORM na chave valorProduto[segunda_metade]
            }
            $primeiroProduto = $primeiroProduto->toArray();
            $segundoProduto = $segundoProduto->toArray();

            $extra = null;

            if (!empty($valorProduto['extra_id'])) {
                $extra = $this->extraModel->where('id', $valorProduto['extra_id'])->first();
                if ($extra === null) {
                    return redirect()->back()
                        ->with('fraude', 'Não foi possível processar a solicitação. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-CUSTOM-3003</strong>'); //FRAUDE NO FORM na chave valorProduto[extra_id]
                }
            }

            // O preço final deve ser sempre calculado no backend.
            $precoPrimeiraMetade = $this->produtoEspecificacaoModel
                ->selectMin('preco')
                ->where('produto_id', $primeiroProduto['id'])
                ->where('customizavel', true)
                ->first();

            if ($precoPrimeiraMetade === null) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível processar a solicitação. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-CUSTOM-4004</strong>');
            }

            $precoSegundaMetade = $this->produtoEspecificacaoModel
                ->selectMin('preco')
                ->where('produto_id', $segundoProduto['id'])
                ->where('customizavel', true)
                ->first();

            if ($precoSegundaMetade === null) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível processar a solicitação. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-CUSTOM-5005</strong>');
            }

            $preco = ((float) $precoPrimeiraMetade->preco / 2)
                + ((float) $precoSegundaMetade->preco / 2)
                + (isset($extra) ? (float) $extra->preco : 0.0);

            $produto = [
                'id' => null,
                'nome' => $primeiroProduto['nome'] . ' / ' . $segundoProduto['nome'] . (isset($extra) ? ' Com extra ' . $extra->nome : ''),
                'slug' => mb_url_title(
                    'custom-' . $primeiroProduto['slug'] . '-' . $segundoProduto['slug'] . (isset($extra) ? '-extra-' . $extra->id : ''),
                    '-',
                    true
                ),
                'preco' => number_format($preco, 2),
                'quantidade' => 1,
                'tamanho' => 'Customizado',
            ];

            if (session()->has('carrinho')) {
                $produtos = session()->get('carrinho');
                $produtosSlugs = array_column($produtos, 'slug');

                if (in_array($produto['slug'], $produtosSlugs)) {
                    $produtos = $this->atualizaProduto('adicionar', $produto['slug'], $produto['quantidade'], $produtos);

                    session()->set('carrinho', $produtos);
                } else {
                    session()->push('carrinho', [$produto]);
                }
            } else {
                $produtos[] = $produto;
                session()->set('carrinho', $produtos);
            }

            return redirect()->to(site_url('carrinho'))
                ->with('sucesso', 'Produto customizado adicionado ao carrinho com sucesso!');
        } else {
            return redirect()->back();
        }
    }
    public function atualizar()
    {
        if ($this->request->getMethod() === 'POST') {
            $produtoPost = $this->request->getPost('produto') ?? [];
            $this->validacao->setRules([
                'produto.slug' => ['label' => 'Produto', 'rules' => 'required|max_length[255]|string'],
                'produto.quantidade' => ['label' => 'Quantidade', 'rules' => 'required|greater_than[0]'],
            ]);
            if (!$this->validacao->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->with('errors_model', $this->validacao->geterrors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }
            $produtos = session()->get('carrinho');
            $produtosSlugs = array_column($produtos, 'slug');

            if (!in_array($produtoPost['slug'], $produtosSlugs)) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ATUA-PROD-7007</strong>'); //FRAUDE NO FORM... chave valorProduto[extra_id] 
            } else {
                $produtos = $this->atualizaProduto($this->acao, $produtoPost['slug'], $produtoPost['quantidade'], $produtos);
                session()->set('carrinho', $produtos);
                return redirect()->back()
                    ->with('sucesso', 'Quantidade do produto atualizada com sucesso!');
            }
        } else {
            return redirect()->back();
        }
    }
    public function remover()
    {
        if ($this->request->getMethod() === 'POST') {
            $produtoPost = $this->request->getPost('produto') ?? [];
            $this->validacao->setRules([
                'produto.slug' => ['label' => 'Produto', 'rules' => 'required|max_length[255]|string'],
            ]);
            if (!$this->validacao->withRequest($this->request)->run()) {
                return redirect()->back()
                    ->with('errors_model', $this->validacao->geterrors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }
            $produtos = session()->get('carrinho');
            $produtosSlugs = array_column($produtos, 'slug');

            if (!in_array($produtoPost['slug'], $produtosSlugs)) {
                return redirect()->back()
                    ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ATUA-PROD-7007</strong>'); //FRAUDE NO FORM... chave valorProduto[extra_id] 
            } else {
                $produtos = $this->removeProduto($produtos, $produtoPost['slug']);
                session()->set('carrinho', $produtos);
                return redirect()->back()
                    ->with('sucesso', 'Produto removido do carrinho com sucesso!');
            }
        } else {
            return redirect()->back();
        }
    }
    public function limpar()
    {
        session()->remove('carrinho');
        return redirect()->back()
            ->with('sucesso', 'Carrinho limpo com sucesso!');
    }
    public function consultaCep()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
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
            ->select(['nome', 'valor_entrega'])
            ->where('ativo', true)
            ->where('slug !=', 'morro-verde')
            ->orderBy('id', 'ASC')
            ->first();

        if ($bairroPadrao === null) {
            $bairroPadrao = $this->bairroModel
                ->select(['nome', 'valor_entrega'])
                ->where('ativo', true)
                ->orderBy('id', 'ASC')
                ->first();
        }

        if ($bairroPadrao === null) {
            $retorno['erro'] = '<span class="text-danger small">Não foi possível calcular a taxa de entrega no momento.</span>';
            return $this->response->setJSON($retorno);
        }

        $bairroMorroVerde = $this->bairroModel
            ->select(['nome', 'valor_entrega'])
            ->where('slug', 'morro-verde')
            ->where('ativo', true)
            ->first();

        $bairroSelecionado = $bairroPadrao;
        $nomeBairroExibicao = $bairroInformado;

        if ($bairroInformadoSlug === 'morro-verde' && $bairroMorroVerde !== null) {
            $bairroSelecionado = $bairroMorroVerde;
            $nomeBairroExibicao = (string) $bairroMorroVerde->nome;
        }

        $retorno['bairro_slug'] = mb_url_title((string) $nomeBairroExibicao, '-', true);
        $retorno['nome_bairro'] = (string) $nomeBairroExibicao;
        $retorno['valor_entrega'] = 'R$ ' . esc(number_format((float) $bairroSelecionado->valor_entrega, 2, ',', '.'));
        $retorno['bairro'] = '<span class="text-success small">Valor de entrega para o bairro ' . esc($nomeBairroExibicao) . ': ' . $retorno['valor_entrega'] . '</span>';
        $carrinho = session()->get('carrinho');
        $total = 0;

        foreach ($carrinho as $produto) {
            $total += $produto['preco'] * $produto['quantidade'];
        }
        $total += (float) $bairroSelecionado->valor_entrega;
        $retorno['total'] = 'R$ ' . esc(number_format($total, 2, ',', '.'));
        return $this->response->setJSON($retorno);
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
    private function removeProduto(array $produtos, string $slug)
    {
        $produtos = array_filter($produtos, function ($linha) use ($slug) {
            return $linha['slug'] !== $slug;
        });

        return $produtos;
    }
    private function categoriaEhLanche(?string $categoriaSlug): bool
    {
        if ($categoriaSlug === null || $categoriaSlug === '') {
            return false;
        }

        return str_contains(mb_strtolower($categoriaSlug), 'lanche');
    }

    private function cidadeEhPratapolis(string $cidade): bool
    {
        if ($cidade === '') {
            return false;
        }

        return mb_url_title($cidade, '-', true) === 'pratapolis';
    }

    private function obterBairroPadraoCidade(string $cidade)
    {
        $bairroCentro = $this->bairroModel
            ->select(['nome', 'valor_entrega'])
            ->where('ativo', true)
            ->where('cidade', $cidade)
            ->groupStart()
            ->where('slug', 'centro')
            ->orWhere('nome', 'Centro')
            ->groupEnd()
            ->first();

        if ($bairroCentro !== null) {
            return $bairroCentro;
        }

        return $this->bairroModel
            ->select(['nome', 'valor_entrega'])
            ->where('ativo', true)
            ->where('cidade', $cidade)
            ->orderBy('valor_entrega', 'ASC')
            ->first();
    }
}
