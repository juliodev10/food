<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Entities\FormaPagamento;

class FormasPagamento extends BaseController
{
    private $formaPagamentoModel;
    public function __construct()
    {
        $this->formaPagamentoModel = new \App\Models\FormaPagamentoModel();
    }
    public function index()
    {
        $data = [
            'titulo' => 'Listando as formas de pagamento',
            'formas' => $this->formaPagamentoModel->withDeleted(true)->paginate(10),
            'pager' => $this->formaPagamentoModel->pager,
        ];
        return view('Admin/FormasPagamento/index', $data);
    }
    public function procurar()
    {
        // Certifique-se que o método 'procurar' existe no seu formaModel
        $formas = $this->formaPagamentoModel->procurar($this->request->getGet('term'));
        $retorno = [];

        foreach ($formas as $forma) {
            $data['id'] = $forma->id;
            $data['value'] = $forma->nome;
            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
    }
    public function criar($id = null)
    {
        $formaPagamento = new FormaPagamento();

        $data = [
            'titulo' => 'Cadastrando uma nova forma de pagamento',
            'forma' => $formaPagamento,
        ];

        return view('Admin/FormasPagamento/criar', $data);
    }
    public function cadastrar()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $formaPagamento = new FormaPagamento($this->request->getPost());

        if ($this->formaPagamentoModel->save($formaPagamento)) {
            return redirect()->to(site_url('admin/formas/show/' . $this->formaPagamentoModel->getInsertID()))
                ->with('sucesso', "Forma de pagamento $formaPagamento->nome cadastrada com sucesso");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->formaPagamentoModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    public function show($id = null)
    {
        $formaPagamento = $this->buscaFormaPagamentoOu404($id);

        $data = [
            'titulo' => "Detalhando a forma de pagamento $formaPagamento->nome",
            'forma' => $formaPagamento,
        ];

        return view('Admin/FormasPagamento/show', $data);
    }
    public function editar($id = null)
    {
        $formaPagamento = $this->buscaFormaPagamentoOu404($id);

        if ($formaPagamento->deletado_em != null) {
            return redirect()->back()->with('info', 'Não é permitido editar uma forma de pagamento excluída.');
        }

        if ($formaPagamento->id == 1) {
            return redirect()->back()->with('info', 'Essa forma de pagamento não pode ser editada.');
        }

        $data = [
            'titulo' => "Editando a forma de pagamento $formaPagamento->nome",
            'forma' => $formaPagamento,
        ];

        return view('Admin/FormasPagamento/editar', $data);
    }
    public function atualizar($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $formaPagamento = $this->buscaFormaPagamentoOu404($id);

        if ($formaPagamento->deletado_em != null) {
            return redirect()->back()->with('info', 'Não é permitido atualizar uma forma de pagamento excluída.');
        }

        if ($formaPagamento->id == 1) {
            return redirect()->back()->with('info', 'Essa forma de pagamento não pode ser atualizada.');
        }

        $formaPagamento->fill($this->request->getPost());

        if (!$formaPagamento->hasChanged()) {
            return redirect()->back()->with('info', 'Nenhum dado foi modificado para atualizar.');
        }

        if ($this->formaPagamentoModel->save($formaPagamento)) {
            return redirect()->to(site_url("admin/formas/show/$formaPagamento->id"))
                ->with('sucesso', "Forma de pagamento $formaPagamento->nome atualizada com sucesso");
        } else {

            return redirect()->back()
                ->with('errors_model', $this->formaPagamentoModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    public function excluir($id = null)
    {
        $formaPagamento = $this->buscaFormaPagamentoOu404($id);

        if ($formaPagamento->id == 1) {
            return redirect()->back()->with('info', 'Essa forma de pagamento não pode ser excluída.');
        }

        if ($formaPagamento->deletado_em != null) {
            return redirect()->back()->with('info', "A forma de pagamento $formaPagamento->nome já está excluída.");
        }

        if ($this->request->is('post')) {
            if ($this->formaPagamentoModel->delete($id)) {
                return redirect()->to(site_url('admin/formas'))
                    ->with('sucesso', "Forma de pagamento $formaPagamento->nome excluída com sucesso!");
            }

            return redirect()->back()
                ->with('errors_model', $this->formaPagamentoModel->errors())
                ->with('atencao', 'Não foi possível excluir a forma de pagamento.');
        }

        $data = [
            'titulo' => "Excluindo a forma de pagamento $formaPagamento->nome",
            'forma' => $formaPagamento,
        ];

        return view('Admin/FormasPagamento/excluir', $data);
    }
    public function desfazerExclusao($id = null)
    {
        $formaPagamento = $this->buscaFormaPagamentoOu404($id);

        if ($formaPagamento->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas formas de pagamento excluídas podem ser restauradas.');
        }

        if ($this->formaPagamentoModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', "Exclusão da forma de pagamento $formaPagamento->nome desfeita com sucesso!");
        }

        return redirect()->back()
            ->with('errors_model', $this->formaPagamentoModel->errors())
            ->with('atencao', 'Por favor, verifique os erros abaixo!')
            ->withInput();
    }
    private function buscaFormaPagamentoOu404(?int $id = null): object
    {
        if (!$id || !$formaPagamento = $this->formaPagamentoModel->withDeleted(true)->find($id)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos a forma de pagamento $id");
        }
        return $formaPagamento;
    }
}
