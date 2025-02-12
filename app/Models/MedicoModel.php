<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicoModel extends Model
{
    protected $table            = 'medicos_sisa';
    protected $primaryKey       = 'matricula';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre','apellido','habilitado'];
    public function obtenerMedicos()
    {
        return $this->findAll();
    }

    public function obtenerCertificadosMedico($matricula, $fechaDesde, $fechaHasta){
        $sql = $this->db->query("SELECT ms.matricula, ms.nombre, ms.apellido, ms.habilitado 
        FROM medicos_sisa ms 
        JOIN certificados c ON '$matricula' = c.matricula
        WHERE c.fechaEmision BETWEEN '".$fechaDesde."' AND '".$fechaHasta."';");
        return $sql->getResult();
        }
}
