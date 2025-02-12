<?php

namespace App\Database\Seeds;

use App\Models\EnfermedadModel;
use CodeIgniter\Database\Seeder;

class EnfermedadesSeeder extends Seeder
{
    public function run()
    {
        $enfermedad = new EnfermedadModel();
        $data = [
                ['nombre' => 'Estrés Laboral', 'idCategoria' => 1],
                ['nombre' => 'Depresión', 'idCategoria' => 1],
                ['nombre' => 'Ansiedad', 'idCategoria' => 1],
                ['nombre' => 'Fatiga Crónica', 'idCategoria' => 1],
                ['nombre' => 'Lumbalgia Aguda', 'idCategoria' => 2],
                ['nombre' => 'Hipertensión Arterial', 'idCategoria' => 1],
                ['nombre' => 'Alergias Estacionales', 'idCategoria' => 1],
                ['nombre' => 'Bronquitis Aguda', 'idCategoria' => 2],
                ['nombre' => 'Gripe', 'idCategoria' => 1],
                ['nombre' => 'Gastroenteritis Aguda', 'idCategoria' => 1],
                ['nombre' => 'Resfriados', 'idCategoria' => 1],
                ['nombre' => 'Dismenorrea', 'idCategoria' => 1],
                ['nombre' => 'Problemas de visión', 'idCategoria' => 1],
                ['nombre' => 'Laringitis aguda', 'idCategoria' => 1],
                ['nombre' => 'Infecciones del tracto urinario', 'idCategoria' => 1],
                ['nombre' => 'Migrañas', 'idCategoria' => 1],
                ['nombre' => 'Lesiones musculares', 'idCategoria' => 2],
                ['nombre' => 'Tendinitis', 'idCategoria' => 2],
                ['nombre' => 'Esguinces', 'idCategoria' => 2],
                ['nombre' => 'Dermatitis de contacto', 'idCategoria' => 1],
                ['nombre' => 'Hospitalizado','idCategoria' => 3,],
                ['nombre' => 'Recuperación de cirugía','idCategoria' => 3,],
                ['nombre' => 'Laringitis aguda','idCategoria' => 1,],
                ['nombre' => 'Infarto Agudo de Miocardio','idCategoria' => 3,],
        ];
        $enfermedad->insertBatch($data);
    }
}
