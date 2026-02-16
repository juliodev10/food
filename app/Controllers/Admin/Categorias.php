<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Categoria;
use CodeIgniter\HTTP\ResponseInterface;

class Categorias extends BaseController
{
    private $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new \App\Models\CategoriaModel();
    }

    public function index()
    {
        $data = [
            'titulo' => 'Categorias',
            'categorias' => $this->categoriaModel->withDeleted(true)->paginate(10),
            'pager' => $this->categoriaModel->pager,
        ];
        return view('Admin/Categorias/index', $data);
    }
    public function procurar()
    {
        // Certifique-se que o método 'procurar' existe no seu CategoriaModel
        $categorias = $this->categoriaModel->procurar($this->request->getGet('term'));
        $retorno = [];

        foreach ($categorias as $categoria) {
            $data['id'] = $categoria->id;
            $data['value'] = $categoria->nome;
            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }
    public function criar()
    {
        $categoria = new Categoria();
        $data = [
            'titulo' => "Cadastrando nova Categoria",
            'categoria' => $categoria,
        ];

        return view('Admin/Categorias/criar', $data);
    }
    public function cadastrar()
    {
        if (!$this->request->is('post')) {
        }
        $categoria = new Categoria($this->request->getPost());

        if ($this->categoriaModel->save($categoria)) {
            return redirect()->to(site_url("admin/categorias/show/" . $this->categoriaModel->getInsertID()))
                ->with('sucesso', "Categoria $categoria->nome cadastrada com sucesso");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->categoriaModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    public function show($id = null)
    {
        $categoria = $this->buscaCategoriaOu404($id);

        $data = [
            'titulo' => "Detalhes da Categoria $categoria->nome",
            'categoria' => $categoria,
        ];

        return view('Admin/Categorias/show', $data);
    }
    public function editar($id = null)
    {
        $categoria = $this->buscaCategoriaOu404($id);

        if ($categoria->deletado_em != null) {
            return redirect()->back()->with('info', 'Não é permitido editar uma categoria excluída. Por favor, restaure a categoria para editá-la.');
        }

        $data = [
            'titulo' => "Editar Categoria $categoria->nome",
            'categoria' => $categoria,
        ];

        return view('Admin/Categorias/editar', $data);
    }
    public function atualizar($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $categoria = $this->buscaCategoriaOu404($id);

        $categoria->fill(
            $this->request->getPost()
        );

        if (!$categoria->hasChanged()) {
            return redirect()->back()->with('info', 'Nenhum dado foi modificado para atualizar.');
        }

        if ($this->categoriaModel->save($categoria)) {
            return redirect()->to(site_url("admin/categorias/show/$categoria->id"))
                ->with('sucesso', "Categoria $categoria->nome atualizada com sucesso");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->categoriaModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    public function excluir($id = null)
    {
        $categoria = $this->buscaCategoriaOu404($id);

        if ($categoria->deletado_em != null) {
            return redirect()->back()->with('info', "A categoria $categoria->nome já está excluída.");
        }
        if ($this->request->getMethod() === 'POST') { // Verifica se confirmou a exclusão
            $this->categoriaModel->delete($id);
            return redirect()->to(site_url('admin/categorias'))->with('sucesso', "Categoria $categoria->nome excluída com sucesso!");
        }

        $data = [
            'titulo' => "Excluindo a Categoria $categoria->nome",
            'categoria' => $categoria,
        ];

        return view('Admin/Categorias/excluir', $data);
    }
    public function desfazerExclusao($id = null)
    {
        $categoria = $this->buscaCategoriaOu404($id);

        if ($categoria->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas categorias excluídas podem ser restauradas.');
        }

        // Certifique-se que o método 'desfazerExclusao' existe no Model
        if ($this->categoriaModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', "Exclusão da categoria $categoria->nome desfeita com sucesso!");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->categoriaModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    private function buscaCategoriaOu404(?int $id = null): object
    {
        if (!$id || !$categoria = $this->categoriaModel->withDeleted(true)->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos a categoria $id");
        }
        return $categoria;
    }
}
