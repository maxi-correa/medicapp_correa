<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('MedicosSeeder');
        $this->call('TiposcategoriasSeeder');
        $this->call('EstadosSeeder');
        $this->call('EmpleadosSeeder');
        $this->call('FamiliaresSeeder');
        $this->call('MedicosauditoresSeeder');
        $this->call('HorariosSeeder');
        $this->call('EnfermedadesSeeder');
        $this->call('CasosSeeder');
        $this->call('CertificadosSeeder');
        $this->call('SeguimientosSeeder');
        $this->call('TurnosSeeder');
    }
}

#Pegar en la terminal, ejecuto todas los seeders
#php spark migrate
#php spark db:seed DatabaseSeeder 