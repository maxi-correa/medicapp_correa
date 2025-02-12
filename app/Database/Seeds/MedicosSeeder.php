<?php

namespace App\Database\Seeds;

use App\Models\MedicoModel;
use CodeIgniter\Database\Seeder;

class MedicosSeeder extends Seeder
{
    public function run()
    {
        $medicos_sisa = new MedicoModel();
        $data = [
            ['matricula' => 10001, 'nombre' => 'Carlos', 'apellido' => 'García', 'habilitado' => true],
            ['matricula' => 10002, 'nombre' => 'Laura', 'apellido' => 'Pérez', 'habilitado' => false],
            ['matricula' => 10003, 'nombre' => 'Martín', 'apellido' => 'Lopez', 'habilitado' => true],
            ['matricula' => 10004, 'nombre' => 'Ana', 'apellido' => 'González', 'habilitado' => true],
            ['matricula' => 10005, 'nombre' => 'Juan', 'apellido' => 'Fernández', 'habilitado' => false],
            ['matricula' => 10006, 'nombre' => 'María', 'apellido' => 'Rodríguez', 'habilitado' => true],
            ['matricula' => 10007, 'nombre' => 'Javier', 'apellido' => 'Hernández', 'habilitado' => true],
            ['matricula' => 10008, 'nombre' => 'Lucía', 'apellido' => 'Martínez', 'habilitado' => true],
            ['matricula' => 10009, 'nombre' => 'Pedro', 'apellido' => 'Sánchez', 'habilitado' => true],
            ['matricula' => 10010, 'nombre' => 'Sofía', 'apellido' => 'Ramírez', 'habilitado' => true],
            ['matricula' => 10011, 'nombre' => 'David', 'apellido' => 'Jiménez', 'habilitado' => true],
            ['matricula' => 10012, 'nombre' => 'Mónica', 'apellido' => 'Torres', 'habilitado' => false],
            ['matricula' => 10013, 'nombre' => 'Fernando', 'apellido' => 'Ruiz', 'habilitado' => true],
            ['matricula' => 10014, 'nombre' => 'Paula', 'apellido' => 'Vargas', 'habilitado' => true],
            ['matricula' => 10015, 'nombre' => 'Roberto', 'apellido' => 'Cruz', 'habilitado' => false],
            ['matricula' => 10016, 'nombre' => 'Sara', 'apellido' => 'Mendoza', 'habilitado' => true],
            ['matricula' => 10017, 'nombre' => 'Andrés', 'apellido' => 'Moreno', 'habilitado' => true],
            ['matricula' => 10018, 'nombre' => 'Clara', 'apellido' => 'Ortiz', 'habilitado' => false],
            ['matricula' => 10019, 'nombre' => 'Enrique', 'apellido' => 'Castro', 'habilitado' => true],
            ['matricula' => 10020, 'nombre' => 'Verónica', 'apellido' => 'Silva', 'habilitado' => true],
            ['matricula' => 10021, 'nombre' => 'Ricardo', 'apellido' => 'Navarro', 'habilitado' => true],
            ['matricula' => 10022, 'nombre' => 'Elena', 'apellido' => 'Suárez', 'habilitado' => false],
            ['matricula' => 10023, 'nombre' => 'Patricia', 'apellido' => 'Romero', 'habilitado' => true],
            ['matricula' => 10024, 'nombre' => 'Miguel', 'apellido' => 'Ibáñez', 'habilitado' => true],
            ['matricula' => 10025, 'nombre' => 'Álvaro', 'apellido' => 'Domínguez', 'habilitado' => false],
            ['matricula' => 10026, 'nombre' => 'Isabel', 'apellido' => 'Castillo', 'habilitado' => true],
            ['matricula' => 10027, 'nombre' => 'Emilio', 'apellido' => 'Rivas', 'habilitado' => true],
            ['matricula' => 10028, 'nombre' => 'Daniela', 'apellido' => 'Mejía', 'habilitado' => false],
            ['matricula' => 10029, 'nombre' => 'Tomás', 'apellido' => 'López', 'habilitado' => true],
            ['matricula' => 10030, 'nombre' => 'Gabriela', 'apellido' => 'Muñoz', 'habilitado' => true],
            ['matricula' => 10031, 'nombre' => 'Paola', 'apellido' => 'Ramirez', 'habilitado' => true],
            ['matricula' => 10032, 'nombre' => 'Pablo', 'apellido' => 'Vera', 'habilitado' => true],
            ['matricula' => 10033, 'nombre' => 'Alexis', 'apellido' => 'Gomes', 'habilitado' => true],
        ];
        $medicos_sisa->insertBatch($data);
        
    }
}
