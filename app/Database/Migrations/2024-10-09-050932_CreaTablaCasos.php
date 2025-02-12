<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaCasos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'numeroTramite'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=> true,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS--------------------
            'fechaInicio'=>[
                'type'=>'DATE',
                'null'=> false,
            ],
            'fechaFin'=>[
                'type'=>'DATE',
                'null'=> false,
            ],
            'motivo'=>[
                'type'=>'TEXT',
                'constraint'=> 200,
                'null'=> false,
            ],
            'corresponde'=>[
                'type'=>'VARCHAR',
                'constraint'=> 15,
                'null'=> false,
            ],
            'tipoCategoriaVigente'=>[
                'type'=>'VARCHAR',
                'constraint'=> 15,
                'null'=> true, 
            ],
            'lugarReposo'=>[
                'type'=>'VARCHAR',
                'constraint'=> 200,
                'null'=> true, 
            ],
            'fechaSugeridaTurno'=>[
                'type'=>'DATE',
                'null'=> true, 
            ],
            #--------------------FORANEAS-------------------- 
            'legajo'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment'=>false,
                'null'=> false,
            ],
            'idFamiliar'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment'=>false,
                'null'=> true,
            ],
            'idEstado'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment'=>false,
                'null'=> false,
            ],
        ]);
        $this->forge->addPrimaryKey('numeroTramite');
        $this->forge->addForeignKey('idEstado', 'estados', 'idEstado');
        $this->forge->addForeignKey(['legajo', 'idFamiliar'], 'familiares_rrhh', ['legajo', 'idFamiliar']);

        $this->forge->createTable('casos');
    }

    public function down()
    {
        $this->forge->dropTable('casos');
    }
}
