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
    private $extraModel;
    private $produtoExtraModel;
    private $medidaModel;
    private $produtoEspecificacaoModel;
    public function __construct()
    {
        $this->produtoModel = new \App\Models\ProdutoModel();
        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->extraModel = new \App\Models\ExtraModel();
        $this->produtoExtraModel = new \App\Models\ProdutoExtraModel();
        $this->medidaModel = new \App\Models\MedidaModel();
        $this->produtoEspecificacaoModel = new \App\Models\ProdutoEspecificacaoModel();
    }
    public function index()
    {
        $data = [
            'titulo' => 'Listando os produtos',
            'produtos' => $this->produtoModel->select('produtos.*, categorias.nome AS categoria')
                ->join('categorias', 'categorias.id = produtos.categoria_id')
                ->withDeleted(true)
                ->paginate(10),
            'especificacoes' => $this->produtoEspecificacaoModel->join('medidas', 'medidas.id = produtos_especificacoes.medida_id')->findAll(),
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
        if ($produto->deletado_em != null) {
            return redirect()->back()->with('info', 'Não é possível editar a imagem de um produto excluído. Por favor, restaure o produto para editar a imagem.');
        }
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

        if ($contentLength > 0 && $postMaxSize > 0 && $contentLength > $postMaxSize) {
            return redirect()->back()->with('atencao', 'O tamanho total do upload excede o limite permitido pelo servidor.');
        }

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
        if ($tamanhoImagem > 9) {
            return redirect()->back()->with('atencao', 'O arquivo selecionado é muito grande. Máximo permitido é 9MB.');
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
    public function excluirExtra($id_principal = null, $id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $produto = $this->buscaProdutoOu404($id);
            $produtoExtra = $this->produtoExtraModel
                ->where('id', $id_principal)
                ->where('produto_id', $produto->id)
                ->first();

            if (!$produtoExtra) {
                return redirect()->back()->with('atencao', 'Extra do produto não encontrado!');
            }

            $this->produtoExtraModel->delete($id_principal);
            return redirect()->back()->with('sucesso', 'Extra removido do produto com sucesso!');
        } else {
            return redirect()->back();
        }
    }
    public function excluirEspecificacao($especificacao_id = null, $produto_id = null)
    {
        $produto = $this->buscaProdutoOu404($produto_id);
        $especificacao = $this->produtoEspecificacaoModel

            ->select('produtos_especificacoes.*, medidas.nome AS medida')
            ->join('medidas', 'medidas.id = produtos_especificacoes.medida_id')
            ->where('produtos_especificacoes.id', $especificacao_id)
            ->where('produtos_especificacoes.produto_id', $produto->id)
            ->first();

        if (!$especificacao) {
            return redirect()->back()->with('atencao', 'Especificação do produto não encontrada!');
        }

        if (strtoupper($this->request->getMethod()) === 'POST') {
            $this->produtoEspecificacaoModel->delete($especificacao_id);

            return redirect()->to(site_url("admin/produtos/especificacoes/$produto->id"))
                ->with('sucesso', 'Especificação removida do produto com sucesso!');
        }

        $data = [
            'titulo' => "Excluir especificação do produto $produto->nome",
            'especificacao' => $especificacao,
        ];

        return view('Admin/Produtos/excluir_especificacao', $data);
    }
    public function excluir($id = null)
    {
        $produto = $this->buscaProdutoOu404($id);
        if ($this->request->getMethod() === 'POST') {
            $this->produtoModel->delete($id);
            if ($produto->imagem) {
                $caminhoImagem = WRITEPATH . 'uploads/produtos/' . $produto->imagem;
                if (is_file($caminhoImagem)) {
                    unlink($caminhoImagem);
                }
            }
            return redirect()->to(site_url('admin/produtos'))->with('sucesso', "Produto $produto->nome excluído com sucesso!");
        }
        $data = [
            'titulo' => "Excluindo o produto $produto->nome",
            'produto' => $produto,
        ];
        return view('Admin/Produtos/excluir', $data);
    }
    public function desfazerExclusao($id = null)
    {
        $produto = $this->buscaprodutoOu404($id);

        if ($produto->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas produtos excluídos podem ser restaurados.');
        }

        // Certifique-se que o método 'desfazerExclusao' existe no Model
        if ($this->produtoModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', "Exclusão da produto $produto->nome desfeita com sucesso!");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->produtoModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
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
    public function extras($id = null)
    {
        $produto = $this->buscaprodutoOu404($id);
        $data = [
            'titulo' => "Gerenciar os extras do produto $produto->nome",
            'produto' => $produto,
            'extras' => $this->extraModel->where('ativo', true)->findAll(),
            'produtoExtras' => $this->produtoExtraModel->buscaExtrasDoProduto($produto->id, 10),
            'pager' => $this->produtoExtraModel->pager,
        ];
        return view('Admin/Produtos/extras', $data);
    }
    public function cadastrarExtras($id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $produto = $this->buscaProdutoOu404($id);
            $produtoExtra['extra_id'] = $this->request->getPost('extra_id');
            $produtoExtra['produto_id'] = $produto->id;
            $produtoExistente = $this->produtoExtraModel
                ->where('produto_id', $produto->id)
                ->where('extra_id', $produtoExtra['extra_id'])
                ->first();

            if ($produtoExistente) {
                return redirect()->back()->with('atencao', 'Extra já adicionado ao produto!');
            }
            if ($this->produtoExtraModel->save($produtoExtra)) {
                return redirect()->back()->with('sucesso', 'Extra adicionado ao produto com sucesso!');
            } else {
                return redirect()->back()
                    ->with('errors_model', $this->produtoExtraModel->errors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }

            dd($produtoExtra);
        } else {
            return redirect()->back();
        }
    }
    public function especificacoes($id = null)
    {
        $produto = $this->buscaprodutoOu404($id);
        $data = [
            'titulo' => "Gerenciar as especificações do produto $produto->nome",
            'produto' => $produto,
            'medidas' => $this->medidaModel->where('ativo', true)->findAll(),
            'produtoEspecificacoes' => $this->produtoEspecificacaoModel->buscaEspecificacoesDoProduto($produto->id, 10),
            'pager' => $this->produtoEspecificacaoModel->pager,
        ];
        return view('Admin/Produtos/especificacoes', $data);
    }
    public function cadastrarEspecificacoes($id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $produto = $this->buscaProdutoOu404($id);
            $especificacao = $this->request->getPost();
            $especificacao['produto_id'] = $produto->id;
            $especificacao['preco'] = str_replace(',', '', $especificacao['preco']);

            $especificacaoExistente = $this->produtoEspecificacaoModel
                ->where('produto_id', $produto->id)
                ->where('medida_id', $especificacao['medida_id'])
                ->first();

            if ($especificacaoExistente) {
                return redirect()->back()->with('atencao', 'Especificação já adicionada ao produto!')->withInput();
            }
            if ($this->produtoEspecificacaoModel->save($especificacao)) {
                return redirect()->back()->with('sucesso', 'Especificação cadastrada com sucesso!');
            } else {
                return redirect()->back()
                    ->with('errors_model', $this->produtoEspecificacaoModel->errors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }

        } else {
            return redirect()->back();
        }
    }
}