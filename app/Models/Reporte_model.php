<?php

namespace App\Models;

use CodeIgniter\Model;

class Reporte_model extends Model // Cambia el nombre de la clase a ReporteModel
{
    protected $table = "casos"; // Nombre correcto de la tabla
    protected $primaryKey = "numeroTramite"; // Clave primaria
    protected $allowedFields = ['numeroTramite', 'fechaInicio', 'fechaFin', 'motivo', 'corresponde', 'tipoCategoriaVigente', 'lugarReposo', 'fechaSugeridaTurno', 'legajo', 'idFamiliar', 'idEstado']; // Campos permitidos

    public function obtenerDatos()
    {
        return $this->findAll(); // Obtener todos los registros
    }

    public function getCasosEmpleados()
    { //Trae los empleados que poseen casos
        $sql = $this->db->query('SELECT c.legajo, e.nombre, e.apellido, e.sector 
        FROM casos c 
        JOIN empleados_rrhh e ON e.legajo = c.legajo');
        return $sql->getResult();
    }


    public function getEmpleadosAusentes()
    {
        $sql = $this->db->query('SELECT e.legajo, e.nombre, e.apellido, e.sector, c.fechaInicio AS "fechaDesde", c.fechaFin AS "fechaHasta", COUNT(c.numeroTramite) AS "Cantidaddeausencias" FROM empleados_rrhh e JOIN casos c ON e.legajo = c.legajo WHERE c.fechaInicio >= "2023-08-15" AND c.fechaFin <= "2024-08-17" 
        GROUP BY e.legajo, e.nombre, e.apellido, c.fechaInicio, c.fechaFin HAVING COUNT(c.numeroTramite) > 0 ORDER BY e.legajo');
        return $sql->getResult();
    }


    //Consulta de empleados con casos ACTIVOS, certificados JUSTIFICADOS

    public function getReporteAusencias()
    {
        $sql = $this->db->query('SELECT e.legajo, e.sector, e.nombre, e.apellido, COUNT(DISTINCT c.numeroTramite) AS Cantidaddeausencias, tc.limitedias FROM empleados_rrhh e LEFT JOIN casos c ON e.legajo = c.legajo LEFT JOIN certificados cert ON c.numeroTramite = cert.numeroTramite LEFT JOIN estados et_caso ON c.idEstado = et_caso.idEstado LEFT JOIN estados et_cert ON cert.idEstado = et_cert.idEstado LEFT JOIN enfermedades en ON cert.codEnfermedad = en.codEnfermedad LEFT JOIN enfermedades en_cat ON en.codEnfermedad = en_cat.codEnfermedad LEFT JOIN tipos_categorias tc ON en_cat.codEnfermedad = tc.idCategoria WHERE e.legajo = 1004  AND et_caso.idEstado = 3  AND et_cert.idEstado = 4 GROUP BY e.legajo, e.sector, e.nombre, e.apellido, tc.limitedias');
        return $sql->getResult();
    }

    public function obtenerCasosPorFechas($fechaInicio, $fechaFin)
    {
        $builder = $this->db->table('casos');
        $builder->select('casos.legajo, empleados_rrhh.sector, empleados_rrhh.nombre, empleados_rrhh.apellido, casos.fechaInicio, casos.fechaFin, COUNT(casos.numeroTramite) as cantidadDeAusencias');
        $builder->join('empleados_rrhh', 'casos.legajo = empleados_rrhh.legajo');
        $builder->where('casos.fechaInicio >=', $fechaInicio);
        $builder->where('casos.fechaFin <=', $fechaFin);
        $builder->groupBy('casos.legajo, empleados_rrhh.sector, empleados_rrhh.nombre, empleados_rrhh.apellido');
    
        return $builder->get()->getResult();
    }

}
