<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Produto as ProdutoEntity;
use App\Models\ProdutoEspecificacaoModel;
use App\Models\ProdutoModel;
use App\Models\ProdutoExtraModel;
use App\Models\MedidaModel;

class Produto extends BaseController
{
    private ProdutoModel $produtoModel;
    private ProdutoEspecificacaoModel $produtoEspecificacaoModel;
    private ProdutoExtraModel $produtoExtraModel;
    private MedidaModel $medidaModel;
    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->produtoEspecificacaoModel = new ProdutoEspecificacaoModel();
        $this->produtoExtraModel = new ProdutoExtraModel();
        $this->medidaModel = new MedidaModel();
    }
    public function detalhes(?string $produto_slug = null)
    {
        if (!$produto_slug || !$produto = $this->produtoModel->where('slug', $produto_slug)->where('ativo', true)->first()) {
            return redirect()->to(site_url('/'));
        }

        $data = [
            'titulo' => "Detalhando o produto $produto->nome",
            'produto' => $produto,
            'especificacoes' => $this->produtoEspecificacaoModel->buscaEspecificacoesDoProdutoDetalhes($produto->id),
        ];

        $extras = $this->produtoExtraModel->BuscaExtrasDoProdutoDetalhes($produto->id);
        if ($extras) {
            $data['extras'] = $extras;
        }

        return view('Produto/detalhes', $data);
    }
    public function customizar(?string $produto_slug = null)
    {
        if (!$produto_slug || !$produto = $this->produtoModel->where('slug', $produto_slug)->where('ativo', true)->first()) {
            return redirect()->back();
        }
        if (!$this->produtoEspecificacaoModel->where('produto_id', $produto->id)->where('customizavel', true)->first()) {
            return redirect()->back()->with('info', 'O produto <strong>' . $produto->nome . '</strong> não possui opções de customização.');
        }
        $data = [
            'titulo' => "Customizando o produto $produto->nome",
            'produto' => $produto,
            'especificacoes' => $this->produtoEspecificacaoModel->buscaEspecificacoesDoProdutoDetalhes($produto->id),
            'opcoes' => $this->produtoModel->exibeOpcoesProdutosParaCustomizar($produto->categoria_id),
        ];
        return view('Produto/customizar', $data);
    }
    public function procurar()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }
        $get = $this->request->getGet();
        $produto = $this->produtoModel->where('id', $get['primeira_metade'])->first();
        if ($produto == null) {
            return $this->response->setJSON([]);
        }

        $data = [
            'produtoSelecionado' => [
                'id' => (int) $produto->id,
                'nome' => $produto->nome,
            ],
            'imagemPrimeiroProduto' => site_url('produto/imagem/' . $produto->id),
        ];

        return $this->response->setJSON($data);
    }
    public function exibeTamanhos()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $data = [];

        $get = $this->request->getGet();
        $primeiroProduto = $this->produtoModel->where('id', $get['primeiro_produto_id'])->first();

        if ($primeiroProduto == null) {
            return $this->response->setJSON([]);
        }
        $especificacoesPrimeiroProduto = $this->produtoEspecificacaoModel->where('produto_id', $primeiroProduto->id)->where('customizavel', true)->findAll();

        if ($especificacoesPrimeiroProduto == null) {
            return $this->response->setJSON([]);
        }
        $extrasPrimeiroProduto = $this->produtoExtraModel->BuscaExtrasDoProdutoDetalhes($primeiroProduto->id) ?? [];
        //--------------------------------------------------- Verificações do segundo produto ---------------------------------------------------//

        $segundoProduto = $this->produtoModel->where('id', $get['segundo_produto_id'])->first();

        if ($segundoProduto == null) {
            return $this->response->setJSON([]);
        }
        $especificacoesSegundoProduto = $this->produtoEspecificacaoModel->where('produto_id', $segundoProduto->id)->where('customizavel', true)->findAll();

        if ($especificacoesSegundoProduto == null) {
            return $this->response->setJSON([]);
        }
        $extrasSegundoProduto = $this->produtoExtraModel->BuscaExtrasDoProdutoDetalhes($segundoProduto->id) ?? [];
        $extrasCombinados = $segundoProduto->combinaExtrasDosProdutos($extrasPrimeiroProduto, $extrasSegundoProduto);
        if ($extrasCombinados != null) {
            $data['extras'] = $extrasCombinados;
        }
        $medidasEmComum = $segundoProduto->recuperaMedidasEmComum($especificacoesPrimeiroProduto, $especificacoesSegundoProduto);
        $medidas = [];
        if (!empty($medidasEmComum)) {
            $medidas = $this->medidaModel->select(['id', 'nome'])->whereIn('id', $medidasEmComum)->where('ativo', true)->findAll();
        }

        $data['medidas'] = $medidas;
        $data['imagemPrimeiroProduto'] = site_url('produto/imagem/' . $primeiroProduto->id);
        $data['imagemSegundoProduto'] = site_url('produto/imagem/' . $segundoProduto->id);
        return $this->response->setJSON($data);
    }
    public function imagem(string $imagem)
    {
        $caminhoImagemPadrao = FCPATH . 'admin/images/Produto-sem-imagem.png';
        $nomeImagem = $imagem;

        if ($imagem && ctype_digit($imagem)) {
            $produto = $this->produtoModel->select('imagem')->find((int) $imagem);
            $nomeImagem = $produto->imagem ?? '';
        }

        $caminhoImagem = $nomeImagem ? WRITEPATH . 'uploads/produtos/' . $nomeImagem : $caminhoImagemPadrao;

        if (!is_file($caminhoImagem)) {
            $caminhoImagem = $caminhoImagemPadrao;
        }

        $infoImagem = new \finfo(FILEINFO_MIME);
        $tipoImagem = $infoImagem->file($caminhoImagem);

        header("Content-Type: $tipoImagem");
        header("Content-Length: " . filesize($caminhoImagem));
        readfile($caminhoImagem);
        exit;
    }
}
