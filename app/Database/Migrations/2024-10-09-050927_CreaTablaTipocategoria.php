<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaTipocategoria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            #--------------------CLAVE PRIMARIA-------------------- 
            'idCategoria'=>[
                'type'=>'INT',
                'unsigned'=> true,
                'auto_increment' => true,
                'null'=> false,
            ],
            #--------------------ATRIBUTOS-------------------- 
            'limitedias'=>[
                'type'=>'INT',
                'null'=> false,
            ],
            'tiposeveridad'=>[
                'type'=>'VARCHAR',
                'constraint' => 50,
                'null'=> false,
            ],
        ]);
        $this->forge->addPrimaryKey('idCategoria');
        $this->forge->createTable('tipos_categorias');
    }

    public function down()
    {
        $this->forge->dropTable('tipos_categorias');
    }
}
