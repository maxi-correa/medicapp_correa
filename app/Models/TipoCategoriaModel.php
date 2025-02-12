<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoCategoriaModel extends Model
{
    protected $table            = 'tipos_categorias';
    protected $primaryKey       = 'idCategoria';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['limitedias','tiposeveridad'];

}
