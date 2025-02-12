<?php

namespace App\Database\Seeds;

use App\Models\FamiliarModel;
use CodeIgniter\Database\Seeder;

class FamiliaresSeeder extends Seeder
{
    public function run()
    {
        $familiarModel=new FamiliarModel();
        $data = [
            [
                'legajo' => 1001,
                'nombre' => 'Lucía',
                'apellido' => 'Pérez',
                'dni' => 35456321,
                'fechaNacimiento' => '2008-03-25',
                'gmail' => 'lucia.perez@gmail.com',
                'telefono' => '1123456720',
                'relacion' => 'Hija',
            ],
            [
                'legajo' => 1001,
                'nombre' => 'Pedro',
                'apellido' => 'Pérez',
                'dni' => 25456123,
                'fechaNacimiento' => '1980-05-12',
                'gmail' => 'pedro.perez@gmail.com',
                'telefono' => '1123456721',
                'relacion' => 'Padre',
            ],
            [
                'legajo' => 1002,
                'nombre' => 'Joaquín',
                'apellido' => 'Gómez',
                'dni' => 38456322,
                'fechaNacimiento' => '2015-09-07',
                'gmail' => 'joaquin.gomez@gmail.com',
                'telefono' => '1123456722',
                'relacion' => 'Hijo',
            ],
            [
                'legajo' => 1003,
                'nombre' => 'Santiago',
                'apellido' => 'López',
                'dni' => 29456323,
                'fechaNacimiento' => '2010-12-01',
                'gmail' => 'santiago.lopez@gmail.com',
                'telefono' => '1123456723',
                'relacion' => 'Hijo',
            ],
            [
                'legajo' => 1004,
                'nombre' => 'Carolina',
                'apellido' => 'Martínez',
                'dni' => 27456324,
                'fechaNacimiento' => '1989-06-15',
                'gmail' => 'carolina.martinez@gmail.com',
                'telefono' => '1123456724',
                'relacion' => 'Esposa',                
            ],
            [
                'legajo' => 1005,
                'nombre' => 'Lucas',
                'apellido' => 'Rodríguez',
                'dni' => 35456325,
                'fechaNacimiento' => '2012-11-28',
                'gmail' => 'lucas.rodriguez@gmail.com',
                'telefono' => '1123456725',
                'relacion' => 'Hijo',                
            ],
            [
                'legajo' => 1006,
                'nombre' => 'Victoria',
                'apellido' => 'Fernández',
                'dni' => 30456326,
                'fechaNacimiento' => '1992-08-19',
                'gmail' => 'victoria.fernandez@gmail.com',
                'telefono' => '1123456726',
                'relacion' => 'Hermana',
            ],
            [
                'legajo' => 1007,
                'nombre' => 'Emilia',
                'apellido' => 'Silva',
                'dni' => 33456327,
                'fechaNacimiento' => '2014-05-20',
                'gmail' => 'emilia.silva@gmail.com',
                'telefono' => '1123456727',
                'relacion' => 'Hija',
            ],
            [
                'legajo' => 1008,
                'nombre' => 'Gabriel',
                'apellido' => 'Castro',
                'dni' => 26456328,
                'fechaNacimiento' => '1987-01-16',
                'gmail' => 'gabriel.castro@gmail.com',
                'telefono' => '1123456728',
                'relacion' => 'Hermano',
            ],
            [
                'legajo' => 1009,
                'nombre' => 'Camila',
                'apellido' => 'García',
                'dni' => 34456329,
                'fechaNacimiento' => '2009-09-05',
                'gmail' => 'camila.garcia@gmail.com',
                'telefono' => '1123456729',
                'relacion' => 'Hija',
            ],
            [
                'legajo' => 1010,
                'nombre' => 'Esteban',
                'apellido' => 'Mendoza',
                'dni' => 32456330,
                'fechaNacimiento' => '1970-12-18',
                'gmail' => 'esteban.mendoza@gmail.com',
                'telefono' => '1123456730',
                'relacion' => 'Padre',
            ],
            [
                'legajo' => 1011,
                'nombre' => 'Daniela',
                'apellido' => 'Ramírez',
                'dni' => 38456331,
                'fechaNacimiento' => '1997-03-22',
                'gmail' => 'daniela.ramirez@gmail.com',
                'telefono' => '1123456731',
                'relacion' => 'Hermana'
            ],
            [
                'legajo' => 1012,
                'nombre' => 'Diego',
                'apellido' => 'Ríos',
                'dni' => 39456332,
                'fechaNacimiento' => '1990-10-14',
                'gmail' => 'diego.rios@gmail.com',
                'telefono' => '1123456732',
                'relacion' => 'Hermano'
            ],
            [
                'legajo' => 1013,
                'nombre' => 'Manuela',
                'apellido' => 'Díaz',
                'dni' => 33456333,
                'fechaNacimiento' => '1985-07-11',
                'gmail' => 'manuela.diaz@gmail.com',
                'telefono' => '1123456733',
                'relacion' => 'Esposa'
            ],
            [
                'legajo' => 1014,
                'nombre' => 'Cecilia',
                'apellido' => 'Morales',
                'dni' => 27456334,
                'fechaNacimiento' => '1988-02-28',
                'gmail' => 'cecilia.morales@gmail.com',
                'telefono' => '1123456734',
                'relacion' => 'Esposa'
            ],
            [
                'legajo' => 1015,
                'nombre' => 'Tomás',
                'apellido' => 'Torres',
                'dni' => 36456335,
                'fechaNacimiento' => '2011-09-02',
                'gmail' => 'tomas.torres@gmail.com',
                'telefono' => '1123456735',
                'relacion' => 'Hijo'
            ],
            [
                'legajo' => 1016,
                'nombre' => 'Juliana',
                'apellido' => 'Vega',
                'dni' => 25456336,
                'fechaNacimiento' => '1995-11-27',
                'gmail' => 'juliana.vega@gmail.com',
                'telefono' => '1123456736',
                'relacion' => 'Hermana'
            ],
            [
                'legajo' => 1017,
                'nombre' => 'Bruno',
                'apellido' => 'Medina',
                'dni' => 31456337,
                'fechaNacimiento' => '1984-04-03',
                'gmail' => 'bruno.medina@gmail.com',
                'telefono' => '1123456737',
                'relacion' => 'Hermano'
            ],
            [
                'legajo' => 1018,
                'nombre' => 'Sofía',
                'apellido' => 'Sánchez',
                'dni' => 35456338,
                'fechaNacimiento' => '1999-06-12',
                'gmail' => 'sofia.sanchez@gmail.com',
                'telefono' => '1123456738',
                'relacion' => 'Hija'
            ],
            [
                'legajo' => 1018,
                'nombre' => 'Patricia',
                'apellido' => 'Sánchez',
                'dni' => 28456339,
                'fechaNacimiento' => '1986-11-19',
                'gmail' => 'patricia.sanchez@gmail.com',
                'telefono' => '1123456788',
                'relacion' => 'Hermana',
            ]
        ];
        foreach ($data as &$familiar) {
            $familiar['idFamiliar'] = $familiarModel->generarIdFamiliar($familiar['legajo']);
            $familiarModel->insert($familiar);
        }
    }
}
