<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaSeguimientos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'idTurno'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'null'=> false,
            ],
            'idSeguimiento'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=> false,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS-------------------- 
            'tipoSeguimiento' => [
                'type' => 'ENUM',
                'constraint' => ['ALTA', 'IRREGULAR','PROXIMO TURNO','EXTENDER PLAZO'],
                'null' => false,
            ],
            'fechaFinOriginalCaso'=>[
                'type'=>'DATE',
                'null'=> false,
            ], 
            'observacion'=>[
                'type'=>'TEXT',
                'constraint'=>255,
                'null' => false,
            ], 
            'diasParaProximoTurno'=>[
                'type'=>'DATE',
                'null'=>true,
            ]
        ]);
        $this->forge->addPrimaryKey(['idTurno', 'idSeguimiento']);
        $this->forge->addForeignKey('idTurno', 'turnos', 'idTurno', 'CASCADE', 'CASCADE');
        $this->forge->createTable('seguimientos');
    }

    public function down()
    {
        $this->forge->dropTable('seguimientos');
    }
}
