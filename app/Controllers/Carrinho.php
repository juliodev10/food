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
    private $horaAtual;
    private $expedienteHoje;
    public function __construct()
    {
        $this->validacao = service('validation');
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->medidaModel = new \App\Models\MedidaModel();
        $this->bairroModel = new \App\Models\BairroModel();
        $this->horaAtual = date('H:i');
        $this->expedienteHoje = $this->recuperaExpedientedeHoje();

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
            if (!$this->empresaAbertaAgora()) {
                return redirect()->back()
                    ->with('atencao', 'Não é possível adicionar produtos ao carrinho fora do horário de funcionamento.');
            }
            $valorProduto = $this->request->getPost('produto') ?? [];

            $validation->setRules([
                'produto.slug' => ['label' => 'Produto', 'rules' => 'required|max_length[30]|string'],
                'produto.especificacao_id' => ['label' => 'Valor do produto', 'rules' => 'required|greater_than[0]|integer'],
                'produto.preco' => ['label' => 'Valor do produto', 'rules' => 'required|greater_than[0]'],
                'produto.quantidade' => ['label' => 'Quantidade', 'rules' => 'required|greater_than[0]'],
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

            $extraIds = $valorProduto['extra_ids'] ?? [];
            if (!is_array($extraIds)) {
                $extraIds = [$extraIds];
            }

            $extraIds = array_values(array_unique(array_filter(array_map(static function ($extraId) {
                return (int) $extraId;
            }, $extraIds), static function ($extraId) {
                return $extraId > 0;
            })));

            $extrasSelecionados = [];
            $extra = null;

            if (!empty($extraIds)) {
                $extrasEncontrados = $this->extraModel->whereIn('id', $extraIds)->findAll();
                $extrasPorId = [];

                foreach ($extrasEncontrados as $extraEncontrado) {
                    $extrasPorId[(int) $extraEncontrado->id] = $extraEncontrado;
                }

                foreach ($extraIds as $extraId) {
                    if (!isset($extrasPorId[$extraId])) {
                        return redirect()->back()
                            ->with('fraude', 'Não foi possível adicionar o produto ao carrinho. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-PROD-2002</strong>'); //FRAUDE NO FORM... chave valorProduto[extra_id]
                    }

                    $extrasSelecionados[] = $extrasPorId[$extraId];
                }

                $extra = $extrasSelecionados[0] ?? null;
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

            $nomesExtras = [];
            $precoExtras = 0.0;
            foreach ($extrasSelecionados as $extraSelecionado) {
                $nomesExtras[] = $extraSelecionado->nome;
                $precoExtras += (float) $extraSelecionado->preco;
            }

            $textoExtras = '';
            $sufixoSlugExtras = '';
            if (!empty($nomesExtras)) {
                $textoExtras = ' Com ' . (count($nomesExtras) === 1 ? 'extra ' : 'extras ') . implode(', ', $nomesExtras);
                $sufixoSlugExtras = '-com-extras-' . implode('-', $extraIds);
            }

            $produto['slug'] = mb_url_title($especificacaoProduto->nome . '-' . $produto['slug'] . $sufixoSlugExtras, '-', true);

            $produto['nome'] = $produto['nome']
                . ($deveExibirInteiro ? ' - ' . $especificacaoProduto->nome : '')
                . $textoExtras;

            $preco = $especificacaoProduto->preco + $precoExtras;
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
            if (!$this->empresaAbertaAgora()) {
                return redirect()->back()
                    ->with('atencao', 'Não é possível adicionar produtos ao carrinho fora do horário de funcionamento.');
            }

            $valorProduto = $this->request->getPost();
            $this->validacao->setRules([
                'primeira_metade' => ['label' => 'Primeiro_produto', 'rules' => 'required|greater_than[0]|integer'],
                'segunda_metade' => ['label' => 'Segundo_produto', 'rules' => 'required|greater_than[0]|integer'],
                'observacao' => ['label' => 'Observação', 'rules' => 'permit_empty|max_length[200]'],
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

            $extraIds = $valorProduto['extra_ids'] ?? [];
            if (!is_array($extraIds)) {
                $extraIds = [$extraIds];
            }

            $extraIds = array_values(array_unique(array_filter(array_map(static function ($extraId) {
                return (int) $extraId;
            }, $extraIds), static function ($extraId) {
                return $extraId > 0;
            })));

            $extrasSelecionados = [];
            if (!empty($extraIds)) {
                $extrasEncontrados = $this->extraModel->whereIn('id', $extraIds)->findAll();
                $extrasPorId = [];

                foreach ($extrasEncontrados as $extraEncontrado) {
                    $extrasPorId[(int) $extraEncontrado->id] = $extraEncontrado;
                }

                foreach ($extraIds as $extraId) {
                    if (!isset($extrasPorId[$extraId])) {
                        return redirect()->back()
                            ->with('fraude', 'Não foi possível processar a solicitação. Por favor,  entre em contato conosco para resolver o problema e informe o código de erro <strong>ERRO-ADD-CUSTOM-3003</strong>'); //FRAUDE NO FORM na chave valorProduto[extra_id]
                    }

                    $extrasSelecionados[] = $extrasPorId[$extraId];
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

            $precoExtras = 0.0;
            $nomesExtras = [];
            foreach ($extrasSelecionados as $extraSelecionado) {
                $precoExtras += (float) $extraSelecionado->preco;
                $nomesExtras[] = $extraSelecionado->nome;
            }

            $preco = ((float) $precoPrimeiraMetade->preco / 2)
                + ((float) $precoSegundaMetade->preco / 2)
                + $precoExtras;

            $observacao = trim((string) ($valorProduto['observacao'] ?? ''));
            $observacao = preg_replace('/\s+/', ' ', $observacao ?? '');

            $textoExtras = '';
            $sufixoSlugExtras = '';
            if (!empty($nomesExtras)) {
                $textoExtras = ' Com ' . (count($nomesExtras) === 1 ? 'extra ' : 'extras ') . implode(', ', $nomesExtras);
                $sufixoSlugExtras = '-extras-' . implode('-', $extraIds);
            }

            $produto = [
                'id' => null,
                'nome' => $primeiroProduto['nome'] . ' / ' . $segundoProduto['nome'] . $textoExtras . ($observacao !== '' ? ' | Obs: ' . $observacao : ''),
                'slug' => mb_url_title(
                    'custom-' . $primeiroProduto['slug'] . '-' . $segundoProduto['slug'] . $sufixoSlugExtras . ($observacao !== '' ? '-obs-' . substr(md5($observacao), 0, 10) : ''),
                    '-',
                    true
                ),
                'preco' => number_format($preco, 2),
                'quantidade' => 1,
                'tamanho' => 'Customizado',
                'observacao' => $observacao,
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
    private function recuperaExpedientedeHoje()
    {
        helper('empresa');
        $expedienteHoje = expedienteHoje();
        return $expedienteHoje;
    }

    private function empresaAbertaAgora(): bool
    {
        if ($this->expedienteHoje === null) {
            return false;
        }

        if (!isset($this->expedienteHoje->situacao) || (int) $this->expedienteHoje->situacao !== 1) {
            return false;
        }

        $abertura = $this->expedienteHoje->abertura ?? null;
        $fechamento = $this->expedienteHoje->fechamento ?? null;

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
