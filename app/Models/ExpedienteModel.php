<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpedienteModel extends Model
{
    protected $table = 'expediente';
    protected $returnType = 'object';
    protected $allowedFields = ['dia_descricao', 'abertura', 'fechamento', 'situacao'];
    protected $validationRules = [
        'abertura' => 'required',
        'fechamento' => 'required',
    ];
}
