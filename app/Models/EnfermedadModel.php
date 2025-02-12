<?php

namespace App\Models;

use CodeIgniter\Model;

class EnfermedadModel extends Model
{
    protected $table            = 'enfermedades';
    protected $primaryKey       = 'codEnfermedad';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre','idCategoria'];
    
    public function obtenerEnfermedades()
    {
        return $this->findAll();
    }

    public function obtenerUnaEnfermedad($enfermdedad) {
        return $this->where('codEnfermedad', $enfermdedad)
        ->first();
    }

    public function obtenerCategoriaPorEnfermedad($codEnfermedad)
    {
        return $this->select('idCategoria') // Selecciona solo la columna 'categoria'
                    ->where('codEnfermedad', $codEnfermedad) // Filtra por 'codEnfermedad'
                    ->first(); // Obtiene el primer resultado
    }
}