<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaTurnos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'idTurno'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment' => true,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS-------------------- 
            'fecha'=>[
                'type'=>'DATE',
                'null'=> false,
            ],
            'hora'=>[
                'type'=>'TIME',
                'null'=> false,
            ],
            'lugar'=>[
                'type'=>'VARCHAR',
                'constraint' => 200,
                'null'=> false,
            ],
            'motivo'=>[
                'type'=>'TEXT',
                'constraint' => 200,
                'null'=> false,
            ],
            #--------------------FORANEAS-------------------- 
            'numeroTramite'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'null'=> false,
            ],
            'matricula'=>[
                'type'=>'VARCHAR',
                'constraint'=> 10,
                'null'=> false,
            ], 
            'idEstado'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'null'=> false,
            ]
        ]);
        $this->forge->addKey('idTurno', true);
        $this->forge->addForeignKey('numeroTramite', 'casos', 'numeroTramite', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('matricula', 'medicos_auditores', 'matricula', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('idEstado', 'estados', 'idEstado');

        $this->forge->createTable('turnos');
    }

    public function down()
    {
        $this->forge->dropTable('turnos');
    }
}
