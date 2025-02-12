<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Adddescripciontocertificados extends Migration
{
    public function up()
    {
        $this->forge->addColumn('certificados', [
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('certificados', 'descripcion');
    }
}
