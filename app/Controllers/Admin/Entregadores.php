<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Entities\Entregador;
use CodeIgniter\Files\FileSizeUnit;

class Entregadores extends BaseController
{
    private $entregadorModel;
    public function __construct()
    {
        $this->entregadorModel = new \App\Models\EntregadorModel();
    }
    public function index()
    {
        $data = [
            'titulo' => 'Listando os entregadores',
            'entregadores' => $this->entregadorModel->withDeleted(true)->paginate(10),
            'pager' => $this->entregadorModel->pager,
        ];
        return view('Admin/Entregadores/index', $data);
    }
    public function procurar()
    {
        // Certifique-se que o método 'procurar' existe no seu EntregadorModel
        $entregadores = $this->entregadorModel->procurar($this->request->getGet('term'));
        $retorno = [];

        foreach ($entregadores as $entregador) {
            $data['id'] = $entregador->id;
            $data['value'] = $entregador->nome;
            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }
    public function criar()
    {
        $entregador = new Entregador();
        $data = [
            'titulo' => "Cadastrando entregador $entregador->nome",
            'entregador' => $entregador,
        ];
        return view('Admin/Entregadores/criar', $data);
    }
    public function cadastrar()
    {
        if ($this->request->getMethod() === 'POST') {
            $entregador = new Entregador($this->request->getPost());
            $entregador->fill($this->request->getPost());

            if ($this->entregadorModel->save($entregador)) {
                return redirect()->to(site_url("admin/entregadores/show/" . $this->entregadorModel->getInsertID()))->with('sucesso', "Entregador $entregador->nome cadastrado com sucesso");
            } else {
                return redirect()->back()->with('errors_model', $this->entregadorModel->errors())->with('atencao', 'Por favor, verifique os erros abaixo e tente novamente')->withInput();
            }
        } else {
            return redirect()->back();
        }
    }
    public function show($id = null)
    {
        $entregador = $this->buscaentregadorOu404($id);
        $data = [
            'titulo' => "Detalhando o entregador $entregador->nome",
            'entregador' => $entregador,
        ];
        return view('Admin/Entregadores/show', $data);
    }
    public function editar($id = null)
    {
        $entregador = $this->buscaentregadorOu404($id);
        $data = [
            'titulo' => "Editando o entregador $entregador->nome",
            'entregador' => $entregador,
        ];
        return view('Admin/Entregadores/editar', $data);
    }
    public function atualizar($id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $entregador = $this->buscaentregadorOu404($id);
            $entregador->fill($this->request->getPost());

            if (!$entregador->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }

            if ($this->entregadorModel->save($entregador)) {
                return redirect()->to(site_url("admin/entregadores/show/$entregador->id"))->with('sucesso', "Perfeito! Entregador $entregador->nome atualizado com sucesso!");
            } else {
                return redirect()->back()->with('errors_model', $this->entregadorModel->errors())->with('atencao', 'Por favor, verifique os erros abaixo e tente novamente')->withInput();
            }
        } else {
            return redirect()->back();
        }
    }
    public function editarImagem($id = null)
    {
        $entregador = $this->buscaentregadorOu404($id);
        if ($entregador->deletado_em != null) {
            return redirect()->back()->with('info', 'Não é possível editar a imagem de um entregador excluído. Por favor, restaure o entregador para editar a imagem.');
        }
        $data = [
            'titulo' => "Editando imagem do entregador $entregador->nome",
            'entregador' => $entregador,
        ];

        return view('Admin/Entregadores/editar_imagem', $data);
    }
    public function upload($id = null)
    {
        $entregador = $this->buscaentregadorOu404($id);
        $uploadError = $_FILES['foto_entregador']['error'] ?? UPLOAD_ERR_NO_FILE;

        $contentLength = (int) ($this->request->getServer('CONTENT_LENGTH') ?? 0);
        $postMaxSize = $this->converteIniSizeParaBytes((string) ini_get('post_max_size'));

        if ($contentLength > 0 && $postMaxSize > 0 && $contentLength > $postMaxSize) {
            return redirect()->back()->with('atencao', 'O tamanho total do upload excede o limite permitido pelo servidor.');
        }

        if ($uploadError === UPLOAD_ERR_NO_FILE) {
            return redirect()->back()->with('atencao', 'Nenhuma imagem foi selecionada para upload');
        }

        $imagem = $this->request->getFile('foto_entregador');

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
        $imagemCaminho = $imagem->store('entregadores');
        $imagemCaminho = WRITEPATH . 'uploads/' . $imagemCaminho;
        /** Fazendo o resize da mesma Imagem*/
        service('image')
            ->withFile($imagemCaminho)
            ->fit(400, 400, 'center')
            ->save($imagemCaminho);

        /* Recuperando a imagem antiga para exluí-la*/
        $imagemAntiga = $entregador->imagem;

        /*Atribuindo a nova imagem*/
        $entregador->imagem = $imagem->getName();
        $this->entregadorModel->save($entregador);

        /**Definindo o caminho da imagem antiga */
        $caminhoImagem = WRITEPATH . 'uploads/entregadores/' . $imagemAntiga;

        if (is_file($caminhoImagem)) {
            unlink($caminhoImagem);
        }
        return redirect()->to(site_url("admin/entregadores/show/$entregador->id"))->with('sucesso', "Perfeito! Imagem do entregador $entregador->nome atualizada com sucesso!");
    }
    public function imagem($imagem = null)
    {
        $caminhoImagem = null;

        if ($imagem) {
            $caminhoUpload = WRITEPATH . 'uploads/entregadores/' . $imagem;
            if (is_file($caminhoUpload)) {
                $caminhoImagem = $caminhoUpload;
            }
        }

        if ($caminhoImagem === null) {
            $caminhoPadrao = FCPATH . 'admin/images/entregador-sem-imagem.webp';
            if (is_file($caminhoPadrao)) {
                $caminhoImagem = $caminhoPadrao;
            }
        }

        if ($caminhoImagem === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Imagem não encontrada');
        }

        $infoImagem = new \finfo(FILEINFO_MIME);
        $tipoImagem = $infoImagem->file($caminhoImagem);
        header("Content-Type: $tipoImagem");
        header("Content-Length: " . filesize($caminhoImagem));
        readfile($caminhoImagem);
        exit;
    }
    public function excluir($id = null)
    {
        $entregador = $this->buscaEntregadorOu404($id);
        if ($this->request->getMethod() === 'POST') {
            $this->entregadorModel->delete($id);
            if ($entregador->imagem) {
                $caminhoImagem = WRITEPATH . 'uploads/entregadores/' . $entregador->imagem;
                if (is_file($caminhoImagem)) {
                    unlink($caminhoImagem);
                }
            }
            return redirect()->to(site_url('admin/entregadores'))->with('sucesso', "entregador $entregador->nome excluído com sucesso!");
        }
        $data = [
            'titulo' => "Excluindo o entregador $entregador->nome",
            'entregador' => $entregador,
        ];
        return view('Admin/Entregadores/excluir', $data);
    }
    public function desfazerExclusao($id = null)
    {
        $entregador = $this->buscaEntregadorOu404($id);

        if ($entregador->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas entregadores excluídos podem ser restaurados.');
        }

        // Certifique-se que o método 'desfazerExclusao' existe no Model
        if ($this->entregadorModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', "Exclusão da entregador $entregador->nome desfeita com sucesso!");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->entregadorModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    private function converteIniSizeParaBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
                break;
        }

        return $value;
    }
    private function buscaentregadorOu404(?int $id = null): object
    {
        if (!$id || !$entregador = $this->entregadorModel->withDeleted(true)->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o entregador $id");
        }
        return $entregador;
    }
}
?>