<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaCertificados extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'idCertificado'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment'=> true,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS-------------------- 
            'fechaEmision'=>[
                'type'=>'DATE',
                'null'=> false,
            ],
            'diasOtorgados'=>[
                'type'=>'DATE',
                'null'=> false,
            ], 
            'lugarReposo'=>[
                'type'=>'VARCHAR',
                'constraint'=>200,
                'null'=> false,
            ], 
            'archivo'=>[
                'type'=>'VARCHAR',
                'constraint'=>255,
                'null'=> false,
            ],
            #--------------------FORANEAS-------------------- 
            'numeroTramite'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'null'=> false,
            ],
            'matricula'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'null'=> true,
            ],
            'codEnfermedad'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'null'=> true,
            ],
            'idEstado'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'null'=> false,
            ],
        ]);
        $this->forge->addKey('idCertificado', true);
        $this->forge->addForeignKey('numeroTramite', 'casos', 'numeroTramite', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('matricula', 'medicos_tratantes', 'matricula', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('codEnfermedad', 'enfermedades', 'codEnfermedad', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('idEstado', 'estados', 'idEstado');
        $this->forge->createTable('certificados');
    }

    public function down()
    {
        $this->forge->dropTable('certificados');
    }
}
