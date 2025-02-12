<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Modificacontraseniaempleados extends Migration
{
    public function up()
    {
        $fields = [
            'contrasenia' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,  // Cambiar a true para permitir NULL
            ]
        ];

        $this->forge->modifyColumn('empleados_rrhh', $fields);
    }

    public function down()
    {
        $fields = [
            'contrasenia' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ]
        ];
        $this->forge->modifyColumn('empleados_rrhh', $fields);
    }
}
