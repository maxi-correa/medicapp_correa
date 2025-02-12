<?php

namespace App\Models;

use CodeIgniter\Model;

class FamiliarModel extends Model
{
    protected $table            = 'familiares_rrhh';
    protected $primaryKey       = 'idFamiliar';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['legajo', 'nombre','apellido','dni','fechaNacimiento','gmail','telefono','relacion'];
    
    public function generarIdFamiliar($legajo)
    {
        $ultimoFamiliar = $this->where('legajo', $legajo)
            ->orderBy('idFamiliar', 'desc')
            ->first();

        return $ultimoFamiliar ? $ultimoFamiliar['idFamiliar'] + 1 : 1;
    }

    public function obtenerFamiliaresDeEmpleado($legajo)
    {
        return $this->where('legajo', $legajo)
                    ->findAll();
    }
}
