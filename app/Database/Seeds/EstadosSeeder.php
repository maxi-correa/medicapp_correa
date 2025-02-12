<?php

namespace App\Database\Seeds;

use App\Models\EstadoModel;
use CodeIgniter\Database\Seeder;

class EstadosSeeder extends Seeder
{
    public function run()
    {
        $estado = new EstadoModel();
        $data = [
            ['tipo'    => 'CASO', 'estado' => 'PENDIENTE'],
            ['tipo'    => 'CASO', 'estado' => 'ACTIVO'],
            ['tipo'    => 'CASO', 'estado' => 'FINALIZADO'],
            ['tipo'    => 'CERTIFICADO', 'estado' => 'INJUSTIFICADO'],
            ['tipo'    => 'CERTIFICADO', 'estado' => 'JUSTIFICADO'],
            ['tipo'    => 'CERTIFICADO', 'estado' => 'PENDIENTE DE REVISION'],
            ['tipo'    => 'TURNO', 'estado' => 'CANCELADO'],
            ['tipo'    => 'TURNO', 'estado' => 'FINALIZADO'],
            ['tipo'    => 'TURNO', 'estado' => 'REPROGRAMADO'],
            ['tipo'    => 'TURNO', 'estado' => 'PENDIENTE'],
            
        ];
        $estado->insertBatch($data);
    }
}
