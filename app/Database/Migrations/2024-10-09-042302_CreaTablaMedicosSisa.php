<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaMedicosSisa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'matricula'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment'=> false,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS-------------------- 
            'nombre'=>[
                'type'=>'VARCHAR',
                'constraint'=> 25,
                'null'=> false,
            ],
            'apellido'=>[
                'type'=>'VARCHAR',
                'constraint'=> 50,
                'null'=> false,
            ], 
            'habilitado'=>[
                'type'=>'BOOLEAN',
                'null'=> false,
                'default' => true,
            ]
        ]);
        $this->forge->addKey('matricula', true);
        $this->forge->createTable('medicos_sisa');
    }

    public function down()
    {
        $this->forge->dropTable('medicos_sisa');
    }
}
