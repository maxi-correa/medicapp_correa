<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaMedicosAuditores extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'matricula'=>[
                'type'=>'VARCHAR',
                'auto_increment'=> false,
                'constraint'=> 10,
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
            'dni'=>[
                'type'=>'INT',
                'constraint'=> 8,
                'null'=> false,
            ],
            'fechaNacimiento'=>[
                'type'=>'DATE',
                'null'=> false,
            ],
            'gmail'=>[
                'type'=>'VARCHAR',
                'constraint'=> 100,
                'null'=> false,
            ],
            'telefono'=>[
                'type'=>'VARCHAR',
                'constraint'=> 15,
                'null'=> false,
            ],
            'especialidad'=>[
                'type'=>'VARCHAR',
                'constraint'=> 50,
                'null'=> false,
            ],
            'contrasenia'=>[
                'type'=>'VARCHAR',
                'constraint'=> 255,
                'null'=> false,
            ]
        ]);
        $this->forge->addPrimaryKey('matricula');
        $this->forge->createTable('medicos_auditores');
    }

    public function down()
    {
        $this->forge->dropTable('medicos_auditores');
    }
}
