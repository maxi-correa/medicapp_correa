<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicoTratanteModel extends Model
{
    protected $table            = 'medicos_tratantes';
    protected $primaryKey       = 'matricula';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['matricula', 'nombre', 'apellido'];

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table($this->table);
    }
    public function getMedicosTratantes()
    {
        return $this->findAll();
    }

    public function obtenerMedicoPorMatricula($matricula)
{
    $sql = $this->db->query(
        "SELECT nombre, apellido FROM medicos_tratantes WHERE matricula = ?",
        [$matricula]
    );
    return $sql->getResult();
}

    public function getMedicosPorFecha($matricula, $fechaInicio, $fechaFin)
    {
        // Asegúrate de utilizar parámetros para evitar inyección de SQL
        $sql = $this->db->query(
            "SELECT mt.matricula, mt.nombre, mt.apellido, COUNT(ct.idCertificado) AS cantidad_certificados
         FROM medicos_tratantes mt
         JOIN certificados ct ON mt.matricula = ct.matricula
         WHERE ct.matricula = ? AND ct.fechaEmision BETWEEN ? AND ?
         GROUP BY mt.matricula, mt.nombre, mt.apellido",
            [$matricula, $fechaInicio, $fechaFin]
        );
        return $sql->getResult();
    }

    public function getCertificadosPorFechaYMedico($fechaDesde, $fechaHasta, $medico)
    {
        // Construir la consulta
        $builder = $this->builder();
        $builder->select('matricula, nombre, apellido, COUNT(*) as cantidad_certificados');
        $builder->join('medicos', 'medicos.matricula = certificados.matricula');
        $builder->where('fecha_emision >=', $fechaDesde);
        $builder->where('fecha_emision <=', $fechaHasta);
        $builder->where('matricula', $medico);
        $builder->groupBy('matricula');
        $query = $builder->get();

        return $query->getResultArray(); // Retorna los resultados como un array
    }

    public function getCertificadosEmitidos($fechaDesde, $fechaHasta, $matricula)
    {
        $sql = $this->db->query("SELECT 
    er.legajo, 
    er.nombre, 
    er.apellido, 
    er.sector,
    COUNT(DISTINCT cr.numeroTramite) AS cantidad_certificados
FROM 
    empleados_rrhh er
JOIN 
    casos c ON er.legajo = c.legajo
LEFT JOIN 
    certificados cr ON c.numeroTramite = cr.numeroTramite

WHERE 
    cr.fechaEmision BETWEEN ? AND ?  -- Rango de fechas
    AND cr.matricula = ?                              -- Matrícula del médico
    AND c.idEstado = 3                                     -- Estado activo del caso
    AND cr.idEstado = 5                                   -- Certificados justificados
GROUP BY 
    er.legajo, er.nombre, er.apellido, er.sector;", [$fechaDesde, $fechaHasta, $matricula]);
        return $sql->getResult();
    }
}
