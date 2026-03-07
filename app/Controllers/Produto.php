<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdutoModel;

class Produto extends BaseController
{
    private ProdutoModel $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
    }
    public function detalhes(?string $produto_slug = null)
    {
        if (!$produto_slug || !$produto = $this->produtoModel->where('slug', $produto_slug)->first()) {
            return redirect()->to(site_url('/'));
        }

        $data = [
            'titulo' => "Detalhando o produto $produto->nome",
            'produto' => $produto,
        ];

        return view('Produto/detalhes', $data);
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
