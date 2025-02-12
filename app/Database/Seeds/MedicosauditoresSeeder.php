<?php

namespace App\Database\Seeds;

use App\Models\MedicoAuditorModel;
use CodeIgniter\Database\Seeder;

class MedicosauditoresSeeder extends Seeder
{
    public function run()
    {
        $medico_auditor = new MedicoAuditorModel();
        $data = [
            ['matricula' => 'M10031', 'nombre' => 'Paola', 'apellido' => 'Ramirez', 'dni' => 12345678, 'fechaNacimiento' => '1980-05-14', 'gmail' => 'paola.ramirez@example.com', 'telefono' => '123456789', 'especialidad' => 'Medicina Ocupacional', 'contrasenia' => '12345'],
            ['matricula' => 'M10032', 'nombre' => 'Pablo', 'apellido' => 'Vera', 'dni' => 87654321, 'fechaNacimiento' => '1985-07-22', 'gmail' => 'pablo.vera@example.com', 'telefono' => '987654321', 'especialidad' => 'Medicina del Trabajo', 'contrasenia' => '12345'],
            ['matricula' => 'M10033', 'nombre' => 'Alexis', 'apellido' => 'Gomes', 'dni' => 23456789, 'fechaNacimiento' => '1990-02-18', 'gmail' => 'alexis.gomes@example.com', 'telefono' => '456789123', 'especialidad' => 'Medico Clinico', 'contrasenia' => '12345'],
        ];
        $data= $medico_auditor->hashPassword($data);
        $medico_auditor->insertBatch($data);
    }
}
