<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaHorarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'matricula'=>[
                'type'=>'VARCHAR',
                'constraint'=> 10,
                'null'=> false,
            ],
            'idHorario'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment'=> false,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS-------------------- 
            'diaSemana'=>[
                'type'=>'VARCHAR',
                'constraint'=> 10,
                'null'=> false,
            ], 
            'horaInicio'=>[
                'type'=>'TIME',
                'null'=> false,
            ],
            'horaFin'=>[
                'type'=>'TIME',
                'null'=> false,
            ],
            'duracion'=>[
                'type'=>'INT',
                'null'=> false,
            ]
        ]);
        $this->forge->addPrimaryKey(['matricula', 'idHorario']);
        $this->forge->addForeignKey('matricula', 'medicos_auditores','matricula','CASCADE', 'CASCADE');
        $this->forge->createTable('horarios');
    }

    public function down()
    {
        $this->forge->dropTable('horarios');
    }
}
