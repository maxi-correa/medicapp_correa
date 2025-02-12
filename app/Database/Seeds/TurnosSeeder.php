<?php

namespace App\Database\Seeds;

use App\Models\TurnoModel;
use CodeIgniter\Database\Seeder;

class TurnosSeeder extends Seeder
{
    public function run()
    {
        $turno = new TurnoModel();
        $data =
        [
            /*['fecha'=>'',
            'hora'=>'',
            'lugar'=>'',
            'motivo'=>'',
            'numeroTramite'=>'',
            'idSeguimiento'=>'',
            'matricula'=>'',
            'idEstado'=>'',
            ],*/
            ['fecha' => '2024-11-15', 'hora' => '12:15', 'lugar' => 'Oficina 2', 'motivo' => 'Consulta médica', 'numeroTramite' => '1', 'matricula' => 'M10032', 'idEstado' => '1'],
            ['fecha' => '2024-11-18', 'hora' => '09:25', 'lugar' => 'Oficina 1', 'motivo' => 'Consulta médica', 'numeroTramite' => '2', 'matricula' => 'M10031', 'idEstado' => '1'], 
            ['fecha' => '2024-11-14', 'hora' => '14:10', 'lugar' => 'Oficina 3', 'motivo' => 'Evaluación anual', 'numeroTramite' => '3', 'matricula' => 'M10032', 'idEstado' => '1'],
            ['fecha' => '2024-11-22', 'hora' => '10:35', 'lugar' => 'Oficina 2', 'motivo' => 'Consulta médica', 'numeroTramite' => '4', 'matricula' => 'M10033', 'idEstado' => '2'],
            /*['fecha' => '2024-11-05', 'hora' => '15:50', 'lugar' => 'Oficina 4', 'motivo' => 'Consulta psicológica', 'numeroTramite' => '00126', 'matricula' => 'M10031', 'idEstado' => '1'],
            ['fecha' => '2024-11-06', 'hora' => '11:15', 'lugar' => 'Oficina 5', 'motivo' => 'Consulta médica', 'numeroTramite' => '00127', 'matricula' => 'M10032', 'idEstado' => '2'],
            ['fecha' => '2024-11-07', 'hora' => '16:45', 'lugar' => 'Oficina 6', 'motivo' => 'Evaluación anual', 'numeroTramite' => '00128', 'matricula' => 'M10033', 'idEstado' => '1'],
            ['fecha' => '2024-11-08', 'hora' => '08:30', 'lugar' => 'Oficina 1', 'motivo' => 'Consulta médica', 'numeroTramite' => '00129', 'matricula' => 'M10031', 'idEstado' => '2'],
            ['fecha' => '2024-11-11', 'hora' => '09:40', 'lugar' => 'Oficina 2', 'motivo' => 'Evaluación anual', 'numeroTramite' => '00130', 'matricula' => 'M10032', 'idEstado' => '1'],
            ['fecha' => '2024-11-11', 'hora' => '15:20', 'lugar' => 'Oficina 3', 'motivo' => 'Consulta médica', 'numeroTramite' => '00131', 'matricula' => 'M10033', 'idEstado' => '2'],
            ['fecha' => '2024-11-12', 'hora' => '10:05', 'lugar' => 'Oficina 4', 'motivo' => 'Consulta psicológica', 'numeroTramite' => '00132', 'matricula' => 'M10031', 'idEstado' => '1'],
            ['fecha' => '2024-11-12', 'hora' => '13:50', 'lugar' => 'Oficina 5', 'motivo' => 'Evaluación anual', 'numeroTramite' => '00133', 'matricula' => 'M10032', 'idEstado' => '2'],
            ['fecha' => '2024-11-13', 'hora' => '08:45', 'lugar' => 'Oficina 6', 'motivo' => 'Consulta médica', 'numeroTramite' => '4', 'matricula' => 'M10033', 'idEstado' => '1'],
            ['fecha' => '2024-11-13', 'hora' => '17:10', 'lugar' => 'Oficina 1', 'motivo' => 'Consulta médica', 'numeroTramite' => '00135', 'matricula' => 'M10031', 'idEstado' => '2'],
            ['fecha' => '2024-11-14', 'hora' => '11:25', 'lugar' => 'Oficina 2', 'motivo' => 'Evaluación anual', 'numeroTramite' => '00136', 'matricula' => 'M10032', 'idEstado' => '1'],
            ['fecha' => '2024-11-14', 'hora' => '16:30', 'lugar' => 'Oficina 3', 'motivo' => 'Consulta psicológica', 'numeroTramite' => '00137', 'matricula' => 'M10033', 'idEstado' => '2'],
            ['fecha' => '2024-11-15', 'hora' => '09:05', 'lugar' => 'Oficina 4', 'motivo' => 'Consulta médica', 'numeroTramite' => '00138', 'matricula' => 'M10031', 'idEstado' => '1'],
            ['fecha' => '2024-11-15', 'hora' => '14:55', 'lugar' => 'Oficina 5', 'motivo' => 'Evaluación anual', 'numeroTramite' => '00139', 'matricula' => 'M10032', 'idEstado' => '2'],
            ['fecha' => '2024-11-15', 'hora' => '08:50', 'lugar' => 'Oficina 6', 'motivo' => 'Consulta médica', 'numeroTramite' => '00140', 'matricula' => 'M10033', 'idEstado' => '1'],
            ['fecha' => '2024-11-15', 'hora' => '17:45', 'lugar' => 'Oficina 1', 'motivo' => 'Consulta psicológica', 'numeroTramite' => '00141', 'matricula' => 'M10031', 'idEstado' => '2'],*/
        ];
        $turno->insertBatch($data);
    }
}
