<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Entities\Bairro;
class Bairros extends BaseController
{
    private $bairroModel;
    public function __construct()
    {
        $this->bairroModel = new \App\Models\BairroModel();
    }
    public function index()
    {
        $data = [
            'titulo' => "Listando os bairros atendidos",
            'bairros' => $this->bairroModel->withDeleted(true)->orderBy('nome', 'ASC')->paginate(10),
            'pager' => $this->bairroModel->pager,
        ];
        return view('Admin/Bairros/index', $data);
    }
    public function procurar()
    {
        if (!$this->request->isAJAX()) {
            exit('Página não encontrada');
        }
        // Certifique-se que o método 'procurar' existe no seu bairroModel
        $bairros = $this->bairroModel->procurar($this->request->getGet('term'));
        $retorno = [];

        foreach ($bairros as $bairro) {
            $data['id'] = $bairro->id;
            $data['value'] = $bairro->nome;
            $retorno[] = $data;
        }
        return $this->response->setJSON($retorno);
    }
    public function criar()
    {
        $bairro = new Bairro();
        $data = [
            'titulo' => "Cadastrando novo bairro",
            'bairro' => $bairro,
        ];
        return view('Admin/Bairros/criar', $data);
    }
    public function cadastrar()
    {
        if ($this->request->getMethod() === 'POST') {
            $bairro = new Bairro($this->request->getPost());
            $bairro->fill($this->request->getPost());
            $bairro->valor_entrega = str_replace([',', 'R$'], ['', ''], $bairro->valor_entrega);

            if ($this->bairroModel->save($bairro)) {
                return redirect()->to(site_url("admin/bairros/show/" . $this->bairroModel->getInsertID()))
                    ->with('sucesso', "Bairro $bairro->nome cadastrado com sucesso");
            } else {
                return redirect()->back()
                    ->with('errors_model', $this->bairroModel->errors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }
        } else {
            return redirect()->back()->with('info', '');
        }
    }
    public function show($id = null)
    {
        $bairro = $this->buscaBairroOu404($id);
        $data = [
            'titulo' => "Detalhes do Bairro $bairro->nome",
            'bairro' => $bairro,
        ];
        return view('Admin/Bairros/show', $data);
    }
    public function editar($id = null)
    {
        $bairro = $this->buscaBairroOu404($id);
        if ($bairro->deletado_em != null) {
            return redirect()->back()->with('info', 'Não é permitido atualizar um bairro excluído. Por favor, restaure o bairro para atualizá-lo.');
        }
        $data = [
            'titulo' => "Editando o Bairro $bairro->nome",
            'bairro' => $bairro,
        ];
        return view('Admin/Bairros/editar', $data);
    }
    public function atualizar($id = null)
    {
        if ($this->request->getMethod() === 'POST') {
            $bairro = $this->buscaBairroOu404($id);

            if ($bairro->deletado_em != null) {
                return redirect()->back()->with('info', 'Não é permitido atualizar um bairro excluído. Por favor, restaure o bairro para atualizá-lo.');
            }

            $bairro->fill($this->request->getPost());
            $bairro->valor_entrega = str_replace([',', 'R$'], ['', ''], $bairro->valor_entrega);

            if (!$bairro->hasChanged()) {
                return redirect()->back()->with('info', 'Não há dados para atualizar');
            }
            if ($this->bairroModel->save($bairro)) {
                return redirect()->to(site_url("admin/bairros/show/$bairro->id"))
                    ->with('sucesso', "Bairro $bairro->nome atualizado com sucesso");
            } else {
                return redirect()->back()
                    ->with('errors_model', $this->bairroModel->errors())
                    ->with('atencao', 'Por favor, verifique os erros abaixo!')
                    ->withInput();
            }
        } else {
            return redirect()->back()->with('info', '');
        }
    }
    public function consultaCep()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to(site_url());
        }

        helper('consulta_cep');
        $retorno = [];

        $cepInformado = (string) $this->request->getGet('cep');
        $cep = preg_replace('/\D/', '', $cepInformado);

        if (strlen($cep) !== 8) {
            $retorno['erro'] = '<span class="text-danger small">Informe um CEP válido.</span>';
            return $this->response->setJSON($retorno);
        }

        $consulta = consultaCep($cep);

        if (!$consulta || (property_exists($consulta, 'servico_indisponivel') && $consulta->servico_indisponivel)) {
            $retorno['erro'] = '<span class="text-danger small">Serviço de CEP indisponível no momento. Tente novamente em instantes.</span>';
            return $this->response->setJSON($retorno);
        }

        if (property_exists($consulta, 'erro') && $consulta->erro) {
            $retorno['erro'] = '<span class="text-danger small">Não foi possível localizar esse CEP.</span>';
            return $this->response->setJSON($retorno);
        }

        $retorno['erro'] = null;
        $retorno['endereco'] = $consulta;

        return $this->response->setJSON($retorno);
    }
    public function excluir($id = null)
    {
        $bairro = $this->buscaBairroOu404($id);

        if ($bairro->deletado_em != null) {
            return redirect()->back()->with('info', "O Bairro $bairro->nome já está excluído.");
        }
        if ($this->request->getMethod() === 'POST') { // Verifica se confirmou a exclusão
            $this->bairroModel->delete($id);
            return redirect()->to(site_url('admin/bairros'))->with('sucesso', "Bairro $bairro->nome excluído com sucesso!");
        }

        $data = [
            'titulo' => "Excluindo o bairro $bairro->nome",
            'bairro' => $bairro,
        ];

        return view('Admin/Bairros/excluir', $data);
    }
    public function desfazerExclusao($id = null)
    {
        $bairro = $this->buscaBairroOu404($id);

        if ($bairro->deletado_em == null) {
            return redirect()->back()->with('info', 'Apenas bairros excluídos podem ser restaurados.');
        }

        // Certifique-se que o método 'desfazerExclusao' existe no Model
        if ($this->bairroModel->desfazerExclusao($id)) {
            return redirect()->back()->with('sucesso', "Exclusão do bairro $bairro->nome desfeita com sucesso!");
        } else {
            return redirect()->back()
                ->with('errors_model', $this->bairroModel->errors())
                ->with('atencao', 'Por favor, verifique os erros abaixo!')
                ->withInput();
        }
    }
    private function buscaBairroOu404(?int $id = null)
    {
        if (!$id || !$bairro = $this->bairroModel->withDeleted(true)->where('id', $id)->first()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o Bairro $id");
        }
        return $bairro;
    }
}
