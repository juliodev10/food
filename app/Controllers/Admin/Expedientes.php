<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Expedientes extends BaseController
{
    private $expedienteModel;
    public function __construct()
    {
        $this->expedienteModel = new \App\Models\ExpedienteModel();
    }
    public function expedientes()
    {
        if ($this->request->getMethod() === 'POST') {
            $postExpedientes = $this->request->getPost();
            $arrayExpedientes = [];

            if (!isset($postExpedientes['id']) || !is_array($postExpedientes['id'])) {
                return redirect()->back()->with('atencao', 'Não foi possível identificar os registros de expediente para atualização.');
            }

            for ($contador = 0; $contador < count($postExpedientes['id']); $contador++) {
                array_push($arrayExpedientes, [
                    'id' => (int) $postExpedientes['id'][$contador],
                    'abertura' => $postExpedientes['abertura'][$contador],
                    'fechamento' => $postExpedientes['fechamento'][$contador],
                    'situacao' => $postExpedientes['situacao'][$contador],
                ]);
            } //fim do for

            $this->expedienteModel->updateBatch($arrayExpedientes, 'id');
            return redirect()->back()->with('sucesso', 'Horário de funcionamento atualizado com sucesso!');
        }
        $data = [
            'titulo' => 'Gerenciar o horário de funcionamento',
            'expedientes' => $this->expedienteModel->findAll(),
        ];
        return view('Admin/Expedientes/expedientes', $data);
    }
}
