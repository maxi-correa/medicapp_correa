<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaEstados extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'idEstado'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=> true,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS-------------------- 
            'tipo'=>[
                'type'=>'VARCHAR',
                'constraint'=> 100,
                'null'=> false,
            ],
            'estado'=>[
                'type'=>'VARCHAR',
                'constraint'=> 100,
                'null'=> false,
            ],
            'descripcion'=>[
                'type'=>'TEXT',
                'constraint'=> 200,
                'null'=> true,
            ]
        ]);
        $this->forge->addKey('idEstado', true);
        $this->forge->createTable('estados');
    }

    public function down()
    {
        $this->forge->dropTable('estados');
    }
}
