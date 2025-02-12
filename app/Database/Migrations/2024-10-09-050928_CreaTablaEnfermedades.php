<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaEnfermedades extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'codEnfermedad'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment' => true,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS-------------------- 
            'nombre'=>[
                'type'=>'VARCHAR',
                'constraint'=> 100,
                'null'=> false,
            ],
            #--------------------FORANEAS-------------------- 
            'idCategoria'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment' => false,
                'null'=> false,
            ],
        ]);
        $this->forge->addKey('codEnfermedad', true);
        $this->forge->addForeignKey('idCategoria', 'tipos_categorias','idCategoria','CASCADE', 'CASCADE');
        $this->forge->createTable('enfermedades');
    }

    public function down()
    {
        $this->forge->dropTable('enfermedades');
    }
}
