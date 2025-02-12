<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpleadoModel extends Model
{
    protected $table            = 'empleados_rrhh';
    protected $primaryKey       = 'legajo';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'apellido', 'dni', 'fechaNacimiento', 'gmail', 'telefono', 'sector', 'rol', 'contrasenia'];

    public function hashPassword(array $data): array
    {
        foreach ($data as &$empleado) {
            if (!empty($empleado['contrasenia'])) {
                $empleado['contrasenia'] = password_hash($empleado['contrasenia'], PASSWORD_BCRYPT);
            }
        }
        return $data;
    }

    #empleado
    public function hashPasswordEmpleado($empleado)
    {
        if (!empty($empleado['contrasenia'])) {
            $empleado['contrasenia'] = password_hash($empleado['contrasenia'], PASSWORD_BCRYPT);
        }
        return $empleado;
    }

    public function obtenerEmpleadoPorCaso($numeroTramite)
    {
        $casoModel = new CasoModel();
        $caso = $casoModel->obtenerCasos($numeroTramite);
        if ($caso) {
            return $this->where('legajo', $caso['legajo'])
                ->first();
        } else {
            return null;
        }
    }

    public function obtenerEmpleado($legajo)
    {
        return $this->where('legajo', $legajo)->first();
    }

    public function obtenerEmpleadoPorLegajoYCorreo($legajo, $correo)
    {
        return $this->where('legajo', $legajo)
            ->where('gmail', $correo)
            ->first();
    }

    public function tiene_caso($legajo)
    {
        $sql = $this->db->query('SELECT COUNT(c.legajo) AS cantidad 
        FROM empleados_rrhh e 
        LEFT JOIN casos c ON e.legajo = c.legajo 
        WHERE e.legajo = ' . $legajo . ' 
        GROUP BY e.legajo');
        return $sql->getResult();
    }


    public function obtenerAusentismos($fechaDesde, $fechaHasta)
    {
        $sql = $this->db->query("SELECT em.legajo, em.nombre, em.apellido, em.sector, 
        sum(DATEDIFF(c.fechaFin, fechaEmision)) + 1 AS ausencias 
        FROM empleados_rrhh em 
        LEFT JOIN casos c on c.legajo = em.legajo 
        LEFT JOIN certificados cr on cr.numeroTramite = c.numeroTramite 
        where cr.idEstado= 5 
        AND c.idEstado=3
        AND cr.fechaEmision BETWEEN  '" . $fechaDesde . "' AND '" . $fechaHasta . "'
         AND c.fechaFin < '" . $fechaHasta . "' 
         GROUP by em.legajo,em.nombre,em.apellido,em.sector
        ORDER BY em.sector ASC;");
        return $sql->getResult();
    }

    public function obtenerSectoresAusencias($fechaDesde, $fechaHasta)
    {
        $sql = $this->db->query("SELECT em.sector, sum(DATEDIFF(c.fechaFin, fechaEmision)+1) AS ausencias
        FROM empleados_rrhh em 
        LEFT JOIN casos c on c.legajo = em.legajo 
        LEFT JOIN certificados cr on cr.numeroTramite = c.numeroTramite 
        where cr.idEstado= 5 
        AND c.idEstado = 3
        AND cr.fechaEmision BETWEEN '" . $fechaDesde . "' AND '" . $fechaHasta . "' 
        AND c.fechaFin < '" . $fechaHasta . "'
        GROUP by em.sector ORDER BY em.sector ASC;");
        return $sql->getResult();
    }

    public function getContraseña($legajo)
    {
        $sql = $this->db->query("SELECT e.contrasenia FROM empleados_rrhh e WHERE e.legajo = '.$legajo.'");
        $result = $sql->getRow();
        if ($result) {
            return $result->contrasenia; // Devolvemos solo la contraseña
        }
        return null; // Si no se encuentra el resultado, devolvemos null
    }

    public function updateContrasena($legajo, $nuevaContrasenaEncriptada)
    {
        // Ejecuta la actualización de la contraseña en la base de datos
        return $this->db->table($this->table)
            ->set('contrasenia', $nuevaContrasenaEncriptada) // Establece el nuevo valor de la contraseña
            ->where('legajo', $legajo) // Filtra por el legajo del empleado
            ->update(); // Realiza la actualizaciónF
    }
}
