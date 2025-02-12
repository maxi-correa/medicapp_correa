<?php

namespace App\Database\Seeds;

use App\Models\TipoCategoriaModel;
use CodeIgniter\Database\Seeder;

class TiposcategoriasSeeder extends Seeder
{
    public function run()
    {
        $tipo_categoria = new TipoCategoriaModel();
        $data = [
            ['limitedias'    => 10, 'tiposeveridad' => 'Simple'],
            ['limitedias'    => 20, 'tiposeveridad' => 'Moderado'],
            ['limitedias'    => 40, 'tiposeveridad' => 'Complejo'],
        ];
        $tipo_categoria->insertBatch($data);
    }
}
