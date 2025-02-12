<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaEmpleadosRrhh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'legajo'=>[
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
            'sector'=>[
                'type'=>'VARCHAR',
                'constraint'=> 100,
                'null'=> false,
            ],
            'rol'=>[
                'type'=>'VARCHAR',
                'constraint'=> 100,
                'null'=> false,
            ],
            'contrasenia'=>[
                'type'=>'VARCHAR',
                'constraint'=> 255,
                'null'=> true,
            ]
        ]);
        $this->forge->addPrimaryKey('legajo');
        $this->forge->createTable('empleados_rrhh');
    }

    public function down()
    {
        $this->forge->dropTable('empleados_rrhh');
    }
}
