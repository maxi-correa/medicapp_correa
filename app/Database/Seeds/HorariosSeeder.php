<?php

namespace App\Database\Seeds;

use App\Models\HorarioModel;
use CodeIgniter\Database\Seeder;

class HorariosSeeder extends Seeder
{
    public function run()
    {
        $horarioModel = new HorarioModel();
        $data = [
            ['matricula' => 'M10031', 'diaSemana' => 'Lunes',    'horaInicio' => '08:00:00', 'horaFin' => '12:00:00', 'duracion' => '30'],
            ['matricula' => 'M10031', 'diaSemana' => 'Miércoles', 'horaInicio' => '08:00:00', 'horaFin' => '12:00:00', 'duracion' => '30'],
            ['matricula' => 'M10031', 'diaSemana' => 'Miércoles',   'horaInicio' => '14:00:00', 'horaFin' => '18:00:00', 'duracion' => '60'],
            ['matricula' => 'M10032', 'diaSemana' => 'Martes',   'horaInicio' => '09:00:00', 'horaFin' => '13:00:00', 'duracion' => '60'],
            ['matricula' => 'M10032', 'diaSemana' => 'Jueves',   'horaInicio' => '14:00:00', 'horaFin' => '18:00:00', 'duracion' => '30'],
            ['matricula' => 'M10033', 'diaSemana' => 'Lunes',    'horaInicio' => '08:00:00', 'horaFin' => '12:00:00', 'duracion' => '30'],
            ['matricula' => 'M10033', 'diaSemana' => 'Miércoles', 'horaInicio' => '13:00:00', 'horaFin' => '17:00:00', 'duracion' => '30'],
            ['matricula' => 'M10033', 'diaSemana' => 'Viernes',   'horaInicio' => '14:00:00', 'horaFin' => '18:00:00', 'duracion' => '30'],
        ];
        foreach ($data as &$horario) {
            $horario['idHorario'] = $horarioModel->generarIdHorario($horario['matricula']);
            $horarioModel->insert($horario);
        }
    }
}
