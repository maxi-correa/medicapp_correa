<?php

namespace App\Database\Seeds;

use App\Models\CasoModel;
use CodeIgniter\Database\Seeder;

class CasosSeeder extends Seeder
{
    public function run()
    {
        $caso= new CasoModel();
        $data = [
            [
                'fechaInicio' => '2024-04-05',
                'fechaFin' => '2024-04-07',
                'motivo' => 'Gripe',
                'corresponde' => 'Propio',
                'legajo' => 1004,
                'idEstado' => 3, 
            ],
            [
                'fechaInicio' => '2024-08-15',
                'fechaFin' => '2024-08-17',
                'motivo' => 'Dolor de cabeza',
                'corresponde' => 'Propio',
                'legajo' => 1024,
                'idEstado' => 3, 
            ],
            [
                'fechaInicio' => '2024-02-20',
                'fechaFin' => '2024-02-23',
                'motivo' => 'Resfrio',
                'corresponde' => 'Propio',
                'legajo' => 1013,
                'idEstado' => 3, 
            ],
            [
                'fechaInicio' => '2024-10-19',
                'fechaFin' => '2024-10-23',
                'motivo' => 'Resfrio',
                'corresponde' => 'Propio',
                'legajo' => 1010,
                'idEstado' => 1, 
            ],
        ];
        $caso->insertBatch($data);
    }
}
