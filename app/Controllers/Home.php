<?php

namespace App\Controllers;

class Home extends BaseController
{
    private $categoriaModel;
    private $produtoModel;
    public function __construct()
    {
        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->produtoModel = new \App\Models\ProdutoModel();
    }
    public function index(): string
    {
        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->produtoModel = new \App\Models\ProdutoModel();

        $data = [
            'titulo' => 'Seja muito bem vindo(a)!',
            'categorias' => $this->categoriaModel->BuscaCategoriasWebHome(),
            'produtos' => $this->produtoModel->BuscaProdutosWebHome(8),
            'produtosGaleria' => $this->produtoModel->buscaProdutosWebGaleria(),
            'pager' => $this->produtoModel->pager,
        ];
        return view('Home/index', $data);
    }


    /*public function email()
    {
        $email = service('email');

        $email->setFrom('your@example.com', 'Your Name');
        $email->setTo('pevay46601@manupay.com1');
        // $email->setCC('another@another-example.com');
        // $email->setBCC('them@their-example.com');

        $email->setSubject('outro teste');
        $email->setMessage('Testing the email class.');
        $email->setMessage('Q onda é essa?');


        if ($email->send()) {
            echo 'Email enviado com sucesso!';
        } else {
            echo $email->printDebugger();
        }
    }*/
}