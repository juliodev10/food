<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Entities\Extra;

class Extras extends BaseController
{
    private $extraModel;
    public function __construct()
    {
        $this->extraModel = new \App\Models\ExtraModel();
    }
    public function index()
    {
        $data = [
            'titulo' => 'Listando os extras de produtos',
            'extras' => $this->extraModel->withDeleted(true)->paginate(10),
            'pager' => $this->extraModel->pager,
        ];
        return view('Admin/Extras/index', $data);
    }
    public function procurar()
    {
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada');
        }
        // Certifique-se que o método 'procurar' existe no seu extraModel
        $extras = $this->extraModel->procurar($this->request->getGet('term'));
        $retorno = [];

        foreach ($extras as $extra) {
            $data['id'] = $extra->id;
            $data['value'] = $extra->nome;
            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }
    public function criar($id = null)
    {
        $extra = new Extra();
        $data = [
            'titulo' => "Detalhes do Extra $extra->nome",
            'extra' => $extra,
        ];

        return view('Admin/Extras/criar', $data);
    }
    public function cadastrar()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $extra = new Extra($this->request->getPost());

        if ($this->extraModel->save($extra)) {
            return redirect()->to(site_url("admin/extras/show/" . $this->extraModel->getInsertID()))
                ->with('sucesso', "Extra $extra->nome cadastrado com sucesso");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->extraModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    public function show($id = null)
    {
        $extra = $this->buscaExtraOu404($id);
        $data = [
            'titulo' => "Detalhes do Extra $extra->nome",
            'extra' => $extra,
        ];

        return view('Admin/Extras/show', $data);
    }
    public function editar($id = null)
    {
        $extra = $this->buscaExtraOu404($id);

        if ($extra->deletado_em != null) {
            return redirect()->back()->with('info', 'Não é permitido editar uma Extra excluída. Por favor, restaure a Extra para editá-la.');
        }

        $data = [
            'titulo' => "Editar Extra $extra->nome",
            'extra' => $extra,
        ];

        return view('Admin/Extras/editar', $data);
    }
    public function atualizar($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $extra = $this->buscaExtraOu404($id);

        if ($extra->deletado_em != null) {
            return redirect()->back()->with('info', 'Não é permitido atualizar um Extra excluído. Por favor, restaure o Extra para atualizá-lo.');
        }
        $extra->fill(
            $this->request->getPost()
        );
        if (!$extra->hasChanged()) {
            return redirect()->back()->with('info', 'Nenhum dado foi modificado para atualizar.');
        }

        if ($this->extraModel->save($extra)) {
            return redirect()->to(site_url("admin/extras/show/$extra->id"))
                ->with('sucesso', "Extra $extra->nome atualizado com sucesso");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->extraModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    public function excluir($id = null)
    {
        $extra = $this->buscaExtraOu404($id);

        if ($extra->deletado_em != null) {
            return redirect()->back()->with('info', "A extra $extra->nome já está excluído.");
        }
        if ($this->request->getMethod() === 'POST') { // Verifica se confirmou a exclusão
            $this->extraModel->delete($id);
            return redirect()->to(site_url('admin/extras'))->with('sucesso', "Extra $extra->nome excluído com sucesso!");
        }

        $data = [
            'titulo' => "Excluindo o Extra $extra->nome",
            'extra' => $extra,
        ];

        return view('Admin/Extras/excluir', $data);
    }
    public function desfazerExclusao($id = null)
    {
        $extra = $this->buscaExtraOu404($id);

        if ($extra->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas extras excluídas podem ser restauradas.');
        }

        // Certifique-se que o método 'desfazerExclusao' existe no Model
        if ($this->extraModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', "Exclusão da extra $extra->nome desfeita com sucesso!");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->extraModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    private function buscaExtraOu404(?int $id = null): object
    {
        if (!$id || !$extra = $this->extraModel->withDeleted(true)->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o extra $id");
        }
        return $extra;
    }
}
