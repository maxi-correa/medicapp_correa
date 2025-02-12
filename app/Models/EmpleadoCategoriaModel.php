<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoCategoriaModel extends Model
{
    protected $table            = 'empleado_categoria';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['legajo', 'idCategoria', 'diasDisponibles'];

    public function obtenerEmpleadoCategoria($legajo, $idCategoria)
    {
        return $this->where('legajo', $legajo)
            ->where('idCategoria', $idCategoria)
            ->first();
    }

    public function eliminarEmpleadoCategoria($legajo, $idCategoria)
    {
        return $this->where('legajo', $legajo)
            ->where('idCategoria', $idCategoria)
            ->first();
    }

    public function actualizarDiasDisponibles($legajo, $diasActualizados, $categoria)
    {
        return $this->set('diasDisponibles', $diasActualizados)
            ->where('legajo', $legajo)
            ->where('idCategoria', $categoria)
            ->update();
    }

    public function obtenerCategoriaDeCaso($legajo, $idCategoria)
    {
        return $this->where('legajo', $legajo)
            ->where('idCategoria', $idCategoria)
            ->first();
    }
}
