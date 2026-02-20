<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoExtraModel extends Model
{
    protected $table = 'produto_extras';
    protected $returnType = 'object';
    protected $allowedFields = ['produto_id', 'extra_id'];

    protected $validationRules = [
        'extra_id' => 'required|integer',
    ];
    protected $validationMessages = [
        'extra_id' => [
            'required' => 'O campo Extra é obrigatório.',
            'integer' => 'O campo Extra deve ser um número inteiro.',
        ],
    ];
}
