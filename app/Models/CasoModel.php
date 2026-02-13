<?php

namespace App\Models;

use CodeIgniter\Model;

class CasoModel extends Model
{
    protected $table            = 'casos';
    protected $primaryKey       = 'numeroTramite';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['fechaInicio', 'fechaFin', 'motivo', 'corresponde', 'tipoCategoriaVigente', 'lugarReposo', 'fechaSugeridaTurno', 'legajo', 'idFamiliar', 'idEstado'];

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table($this->table);
    }

    public function get_casos_finalizados()
    {
        $sql = $this->db->query("SELECT c.legajo, c.numeroTramite, c.corresponde as tipoCertificado, 
        c.fechaInicio as fechaAusencia, er.nombre, er.apellido, 
        CASE WHEN cer.idCertificado 
        IS NOT NULL THEN 'SI' 
        ELSE 'NO' END AS disponeCertificado 
        FROM `casos` c 
        LEFT JOIN empleados_rrhh er ON er.legajo=c.legajo 
        LEFT JOIN certificados cer ON cer.numeroTramite = c.numeroTramite 
        WHERE c.idEstado=3;");
        return $sql->getResult();
    }

    public function get_casos_pendientes()
    {
        $sql = $this->db->query("SELECT DISTINCT c.legajo, c.numeroTramite, c.corresponde as tipoCertificado, 
                                c.fechaInicio as fechaAusencia, er.nombre, er.apellido, 
                                CASE WHEN cer.idCertificado IS NOT NULL 
                                THEN 'SI' 
                                ELSE 'NO' 
                                END AS disponeCertificado 
                                FROM `casos` c 
                                LEFT JOIN empleados_rrhh er ON er.legajo=c.legajo 
                                LEFT JOIN certificados cer ON cer.numeroTramite = c.numeroTramite 
                                WHERE c.idEstado=1");
        return $sql->getResult();
    }

    public function get_casos_activos()
    {
        $sql = $this->db->query("SELECT DISTINCT c.legajo, c.numeroTramite, c.corresponde as tipoCertificado, 
        c.fechaInicio as fechaAusencia, er.nombre, er.apellido, 
        c.tipoCategoriaVigente, tc.tiposeveridad, c.fechaSugeridaTurno,
        CASE WHEN cer.idCertificado IS NOT NULL THEN 'SI' 
        ELSE 'NO' END AS disponeCertificado 
        FROM `casos` c LEFT JOIN empleados_rrhh er ON er.legajo=c.legajo 
        LEFT JOIN certificados cer ON cer.numeroTramite = c.numeroTramite 
        LEFT JOIN turnos t on t.numeroTramite = c.numeroTramite 
        LEFT JOIN tipos_categorias tc on tc.idCategoria = c.tipoCategoriaVigente 
        WHERE c.idEstado=2;");
        return $sql->getResult();
    }


    public function obtenerUnCaso($nroTramite)
    {
        $sql = $this->db->query("SELECT DISTINCT c.numeroTramite, c.legajo, c.corresponde as tipoCertificado, CONCAT(em.nombre, ' ' ,em.apellido) as empleado, c.fechaInicio as fechaAusencia, c.fechaFin, cr.lugarReposo, 
        c.motivo, tc.tiposeveridad, CONCAT(fr.nombre, ' ', fr.apellido) as pacienteFamiliar, 
        CASE WHEN c.corresponde = 'Familiar' THEN fr.dni ELSE em.dni END AS dniPaciente 
        FROM `casos` c INNER JOIN empleados_rrhh em on c.legajo= em.legajo 
        LEFT JOIN tipos_categorias tc on tc.idCategoria=c.tipoCategoriaVigente LEFT JOIN certificados cr on cr.numeroTramite = c.numeroTramite 
        LEFT JOIN familiares_rrhh fr ON fr.idFamiliar = c.idFamiliar AND em.legajo = fr.legajo 
        WHERE c.numeroTramite=" . $nroTramite);

        return $sql->getResult();
    }

    public function get_lugar_certificado($nroTramite)
    {
        $sql = $this->db->query("SELECT cr.lugarReposo 
                                FROM `casos` c 
                                LEFT JOIN certificados cr on cr.numeroTramite = c.numeroTramite 
                                WHERE c.numeroTramite =" . $nroTramite . " 
                                ORDER BY cr.fechaEmision DESC LIMIT 1;");
        return $sql->getResult();
    }

    public function obtenerCasos($numeroTramite)
    {
        return $this->find($numeroTramite);
    }

    public function obtenerCasoEnCurso($legajo)
    {
        return $this->where('legajo', $legajo)
            ->where('idEstado !=', 'FINALIZADO')
            ->where('tipo', 'CASO')
            ->orderBy('fechaInicio', 'DESC')
            ->findAll();
    }

    /*public function get_casos_por_legajo($legajo) {
        $query = $this->db->query("SELECT c.numeroTramite, est.estado AS estadoCaso,
            c.fechaInicio AS fechaAusencia, c.motivo, enf.nombre,
            categEnf.tipoSeveridad AS tipoEnfermedad,
            med.nombre AS medicoTratante, 
            ma.nombre AS medicoAuditor
        FROM casos AS c
        JOIN estados AS est ON c.idEstado = est.idEstado
        
        LEFT JOIN certificados AS cert ON c.idCertificado = cert.idCertificado
        LEFT JOIN enfermedades AS enf ON cert.codEnfermedad = enf.codEnfermedad
        LEFT JOIN tipos_categorias as categEnf ON categEnf.idCategoria = enf.idCategoria
        LEFT JOIN medicos_auditores AS med ON cert.matricula = med.matricula
        LEFT JOIN seguimientos AS seg ON c.nroTramite = seg.nroTramite
        LEFT JOIN medicos_auditores AS ma ON t.matricula = ma.matricula
        WHERE c.legajo = ?
        ORDER BY c.fechaInicio ASC
        ", [$legajo]);
        
    return $query->getResultArray();    
    }
    */
    public function get_casos_por_legajo($legajo)
    {
        $query = $this->db->query("SELECT c.numeroTramite, c.legajo, est.estado AS estadoCaso, c.motivo, c.fechaInicio as fechaAusencia, c.fechaFin, tipoCat.tipoSeveridad as severidadEnfermedad, CONCAT(medTrat.nombre, ' ', medTrat.apellido) AS medicoTratante, c.numeroTramite as personalCierre, c.numeroTramite as nombreCompEmpCierre
    FROM casos as c
    JOIN estados as est ON c.idEstado = est.idEstado
    LEFT JOIN certificados as cert ON cert.numeroTramite = c.numeroTramite
    LEFT JOIN enfermedades as enf ON enf.codEnfermedad = cert.codEnfermedad
    LEFT JOIN tipos_categorias as tipoCat ON enf.idCategoria = tipoCat.idCategoria
    LEFT JOIN medicos_tratantes as medTrat ON medTrat.matricula = cert.matricula
    WHERE c.legajo = ?
    ", [$legajo]);
        return $query->getResultArray();
    }


    public function obtenerCasoActivo($legajo)
    {
        $sql = $this->db->query("SELECT c.numeroTramite as casoActivo
                                FROM `casos` c 
                                LEFT JOIN estados e on e.idEstado = c.idEstado 
                                WHERE c.legajo=" . $legajo . " 
                                AND e.estado !='FINALIZADO' 
                                AND e.tipo = 'CASO';");
        return $sql->getResult();
    }

    public function actualizarEstadocaso($nroTramite, $estado)
    {
        return $this->set('idEstado', $estado)
            ->where('numeroTramite', $nroTramite)
            ->update();
    }

    public function actualizarCaso($nroTramite, $fechaFin, $categoria, $motivo, $lugarReposo)
    {
        return
            $this->set('fechaFin', $fechaFin)
            ->set('tipoCategoriaVigente', $categoria)
            ->set('motivo', $motivo)
            ->set('lugarReposo', $lugarReposo)
            ->where('numeroTramite', $nroTramite)
            ->update();
    }

    //######### FUNCIONES PARA EL HISTORIAL DE EMPLEADO #########################################
    public function get_casos_finalizados_empleado($legajo)
    {
        $sql = $this->db->query("SELECT c.legajo, c.numeroTramite, c.corresponde as tipoCertificado, c.fechaFin, 
        c.fechaInicio as fechaAusencia, er.nombre, er.apellido, tc.tiposeveridad, cer.idCertificado, cer.idEstado,
        CASE WHEN cer.idCertificado 
        IS NOT NULL THEN 'SI' 
        ELSE 'NO' END AS disponeCertificado 
        FROM `casos` c 
        LEFT JOIN empleados_rrhh er ON er.legajo=c.legajo 
        LEFT JOIN certificados cer ON cer.numeroTramite = c.numeroTramite
        LEFT JOIN tipos_categorias tc on tc.idCategoria = c.tipoCategoriaVigente 
        WHERE c.idEstado=3 AND c.legajo=" . $legajo . ";");
        return $sql->getResultArray();
    }

    public function get_casos_pendientes_empleado($legajo)
    {
        $sql = $this->db->query("SELECT DISTINCT c.legajo, c.numeroTramite, c.corresponde as tipoCertificado, 
                                c.fechaInicio as fechaAusencia, er.nombre, er.apellido, cer.idCertificado, tc.tiposeveridad,
                                CASE WHEN cer.idCertificado IS NOT NULL 
                                THEN 'SI' 
                                ELSE 'NO' 
                                END AS disponeCertificado 
                                FROM `casos` c 
                                LEFT JOIN empleados_rrhh er ON er.legajo=c.legajo 
                                LEFT JOIN certificados cer ON cer.numeroTramite = c.numeroTramite 
                                LEFT JOIN tipos_categorias tc on tc.idCategoria = c.tipoCategoriaVigente
                                WHERE c.idEstado=1 AND c.legajo=" . $legajo . ";");
        return $sql->getResultArray();
    }

    public function get_casos_activos_empleado($legajo)
    {
        $sql = $this->db->query("SELECT DISTINCT c.legajo, c.numeroTramite, c.corresponde as tipoCertificado, 
        c.fechaInicio as fechaAusencia, er.nombre, er.apellido, 
        c.tipoCategoriaVigente, tc.tiposeveridad, c.fechaSugeridaTurno, cer.idCertificado, c.fechaFin,
        CASE WHEN cer.idCertificado IS NOT NULL THEN 'SI' 
        ELSE 'NO' END AS disponeCertificado 
        FROM `casos` c LEFT JOIN empleados_rrhh er ON er.legajo=c.legajo 
        LEFT JOIN certificados cer ON cer.numeroTramite = c.numeroTramite 
        LEFT JOIN turnos t on t.numeroTramite = c.numeroTramite 
        LEFT JOIN tipos_categorias tc on tc.idCategoria = c.tipoCategoriaVigente 
        WHERE c.idEstado=2 AND c.legajo=" . $legajo . ";");
        return $sql->getResultArray();
    }

    //########## FUNCIÓN PARA NOTIFICACIÓN DE ADMIN #########################################
    public function contarCasosSinTurnoSugerido(): int
    {
        return $this
            ->where('idEstado', 2) // Activos
            ->whereIn('tipoCategoriaVigente', [2, 3]) // Moderada o Grave
            ->where('fechaSugeridaTurno IS NULL', null, false)
            ->countAllResults();
    }


    //TODO PRUEBA DE URL OCULTA
    public function getCasos()
    {
        return $this->findAll();
    }

    public function getCasoPorNumeroTramite($numeroTramite)
    {
        return $this->where('numeroTramite', $numeroTramite)->first();
    }

    public function obtenerTipoCategoriaVigente($numeroTramite)
    {
        return $this->where('numeroTramite', $numeroTramite)
            ->select('tipoCategoriaVigente')
            ->first();
    }
}
