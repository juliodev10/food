<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Produto;
use CodeIgniter\Files\FileSizeUnit;
helper("number");

class Produtos extends BaseController
{
    private $produtoModel;
    private $categoriaModel;
    public function __construct()
    {
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->categoriaModel = new \App\Models\CategoriaModel();
    }
    public function index()
    {
        $data = [
            'titulo' => 'Listando os produtos',
            'produtos' => $this->produtoModel->select('produtos.*, categorias.nome AS categoria')
                ->join('categorias', 'categorias.id = produtos.categoria_id')
                ->withDeleted(true)
                ->paginate(10),
            'pager' => $this->produtoModel->pager,
        ];
        return view('Admin/Produtos/index', $data);
    }
    public function procurar()
    {
        // Certifique-se que o método 'procurar' existe no seu produtoModel
        $produtos = $this->produtoModel->procurar($this->request->getGet('term'));
        $retorno = [];

        foreach ($produtos as $produto) {
            $data['id'] = $produto->id;
            $data['value'] = $produto->nome;
            $retorno[] = $data;
        }
        return $this->response->setJSON($retorno);
    }
    public function criar($id = null)
    {
        $produto = new Produto();
        $data = [
            'titulo' => "Criando novo produto",
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll(),
        ];

        return view('Admin/Produtos/criar', $data);
    }
    public function cadastrar()
    {
        if ($this->request->getMethod() === 'POST') {
            $produto = new Produto($this->request->getPost());
            if ($this->produtoModel->save($produto)) {
                return redirect()->to(site_url("admin/produtos/show/" . $this->produtoModel->getInsertID()))->with('sucesso', "Produto $produto->nome cadastrado com sucesso!");
            } else {
                return redirect()->back()
                    ->with('errors_model', $this->produtoModel->errors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }
    public function show($id = null)
    {
        $produto = $this->buscaprodutoOu404($id);
        $data = [
            'titulo' => "Detalhes do produto $produto->nome",
            'produto' => $produto,
        ];

        return view('Admin/Produtos/show', $data);
    }
    public function editar($id = null)
    {
        $produto = $this->buscaprodutoOu404($id);
        $data = [
            'titulo' => "Editando o produto $produto->nome",
            'produto' => $produto,
            'categorias' => $this->categoriaModel->where('ativo', true)->findAll(),
        ];

        return view('Admin/Produtos/editar', $data);
    }
    public function atualizar($id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $produto = $this->buscaProdutoOu404($id);
            $produto->fill($this->request->getPost());
            if (!$produto->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }
            if ($this->produtoModel->save($produto)) {
                return redirect()->to(site_url("admin/produtos/show/$id"))->with('sucesso', 'Produto atualizado com sucesso!');
            } else {
                return redirect()->back()
                    ->with('errors_model', $this->produtoModel->errors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }
        } else {
            return redirect()->back();
        }
    }
    public function editarImagem($id = null)
    {
        $produto = $this->buscaprodutoOu404($id);
        $data = [
            'titulo' => "Editando imagem do produto $produto->nome",
            'produto' => $produto,
        ];

        return view('Admin/Produtos/editar_imagem', $data);
    }
    public function upload($id = null)
    {
        $produto = $this->buscaprodutoOu404($id);

        // Lê o erro diretamente do PHP antes de qualquer abstração do CI4,
        // pois quando upload_max_filesize é excedido o CI4 pode retornar null.
        $uploadError = $_FILES['foto_produto']['error'] ?? UPLOAD_ERR_NO_FILE;

        // POST body maior que post_max_size: PHP zera $_FILES e $_POST
        $contentLength = (int) ($this->request->getServer('CONTENT_LENGTH') ?? 0);
        $postMaxSize = $this->converteIniSizeParaBytes((string) ini_get('post_max_size'));

        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            return redirect()->back()->with('atencao', 'Nenhuma imagem foi selecionada para upload');
        }

        $imagem = $this->request->getFile('foto_produto');

        if (!$imagem->isValid()) {
            $codigoErro = $imagem->getError();
            if ($codigoErro === UPLOAD_ERR_NO_FILE) {
                return redirect()->back()->with('atencao', 'Nenhum arquivo foi selecionado');
            }
        }

        $tamanhoImagem = $imagem->getSizeByMetricUnit(FileSizeUnit::MB, 2);
        if ($tamanhoImagem > 2) {
            return redirect()->back()->with('atencao', 'O arquivo selecionado é muito grande. Máximo permitido é 2MB.');
        }
        $tipoImagem = $imagem->getMimeType();

        $tipoImagemLimpo = explode('/', (string) $tipoImagem);
        $tipoPermitidos = [
            'jpeg',
            'png',
            'gif',
            'webp',
        ];

        if (count($tipoImagemLimpo) < 2 || !in_array($tipoImagemLimpo[1], $tipoPermitidos, true)) {
            return redirect()->back()->with('atencao', 'Tipo de imagem não permitido. Apenas: ' . implode(', ', $tipoPermitidos));
        }
        list($largura, $altura) = getimagesize($imagem->getPathname());
        if ($largura < "400" || $altura < "400") {
            return redirect()->back()->with('atencao', 'A imagem selecionada é muito pequena. Mínimo permitido é 400x400 pixels.');
        }
        /*Fazendo o store da imagem e recuperando o caminho da mesma*/
        $imagemCaminho = $imagem->store('produtos');
        $imagemCaminho = WRITEPATH . 'uploads/' . $imagemCaminho;
        /** Fazendo o resize da mesma Imagem*/
        service('image')
            ->withFile($imagemCaminho)
            ->fit(400, 400, 'center')
            ->save($imagemCaminho);

        /* Recuperando a imagem antiga para exluí-la*/
        $imagemAntiga = $produto->imagem;

        /*Atribuindo a nova imagem*/
        $produto->imagem = $imagem->getName();
        $this->produtoModel->save($produto);

        /**Definindo o caminho da imagem antiga */
        $caminhoImagem = WRITEPATH . 'uploads/produtos/' . $imagemAntiga;

        if (is_file($caminhoImagem)) {
            unlink($caminhoImagem);
        }
        return redirect()->to(site_url("admin/produtos/show/$produto->id"))->with('sucesso', 'Imagem do produto atualizada com sucesso!');
    }
    public function imagem($imagem = null)
    {
        if ($imagem) {
            $caminhoImagem = WRITEPATH . 'uploads/produtos/' . $imagem;
            $infoImagem = new \finfo(FILEINFO_MIME);
            $tipoImagem = $infoImagem->file($caminhoImagem);
            header("Content-Type: $tipoImagem");
            header("Content-Length: " . filesize($caminhoImagem));
            readfile($caminhoImagem);
            exit;
        }
    }
    private function converteIniSizeParaBytes(string $valor): int
    {
        $valor = trim($valor);

        if ($valor === '') {
            return 0;
        }

        $numero = (int) $valor;
        $unidade = strtolower(substr($valor, -1));

        return match ($unidade) {
            'g' => $numero * 1024 * 1024 * 1024,
            'm' => $numero * 1024 * 1024,
            'k' => $numero * 1024,
            default => (int) $valor,
        };
    }
    private function buscaProdutoOu404(?int $id = null): object
    {
        if (
            !$id || !$produto = $this->produtoModel->select('produtos.*, categorias.nome AS categoria')
                ->join('categorias', 'categorias.id = produtos.categoria_id')
                ->where('produtos.id', $id)
                ->withDeleted(true)->first()
        ) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o produto $id");
        }
        return $produto;
    }
}
