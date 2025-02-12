<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaFamiliaresRrhh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'legajo'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment'=>false,
                'null'=> false,
            ],
            'idFamiliar'=>[
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
            'relacion'=>[
                'type'=>'VARCHAR',
                'constraint'=> 100,
                'null'=> false,
            ]
        ]);
        $this->forge->addPrimaryKey(['legajo', 'idFamiliar']);
        $this->forge->addForeignKey('legajo', 'empleados_rrhh', 'legajo', 'CASCADE', 'CASCADE');
        $this->forge->createTable('familiares_rrhh');
    }

    public function down()
    {
        $this->forge->dropTable('familiares_rrhh');
    }
}
