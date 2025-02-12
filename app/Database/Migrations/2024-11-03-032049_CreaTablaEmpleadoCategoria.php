<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreaTablaEmpleadoCategoria extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment'=> true,
                'null' => false,
            ],
            #--------------------CLAVES PRIMARIAS-------------------- 
            'legajo' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'idCategoria' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            #--------------------ATRIBUTOS--------------------
            'diasDisponibles' => [
                'type' => 'INT',
                'null' => false,
                'default' => 0,
            ]
        ]);
        
        $this->forge->addPrimaryKey(['id']);

        $this->forge->addForeignKey('legajo', 'empleados_rrhh', 'legajo', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('idCategoria', 'tipos_categorias', 'idCategoria', 'CASCADE', 'CASCADE');

        # Crear la tabla
        $this->forge->createTable('empleado_categoria');
    }

    public function down()
    {
        $this->forge->dropTable('empleado_categoria');
    }
}
