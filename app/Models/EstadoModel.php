<?php

namespace App\Models;

use CodeIgniter\Model;

class EstadoModel extends Model
{
    protected $table            = 'estados';
    protected $primaryKey       = 'idEstado';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['tipo','estado','descripcion'];

    public function get_estados_certificados() {
        $sql = $this->db->query("SELECT e.idEstado, e.estado 
                                FROM `estados` e 
                                WHERE e.tipo = 'CERTIFICADO'
                                AND e.estado  != 'PENDIENTE DE REVISION';");
        return $sql->getResult();
    }

}
