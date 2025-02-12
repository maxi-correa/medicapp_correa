<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificadoModel extends Model
{
    protected $table            = 'certificados';
    protected $primaryKey       = 'idCertificado';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['fechaEmision','diasOtorgados','lugarReposo','archivo','numeroTramite','matricula','codEnfermedad','idEstado','descripcion'];


    public function get_certificados($nroTramite) {
        $sql = $this->db->query("SELECT cr.idCertificado, e.nombre , cr.fechaEmision, cr.diasOtorgados, 
                                cr.lugarReposo, cr.archivo, es.estado, es.estado, cr.descripcion,
                                mt.matricula, CONCAT(mt.nombre, ' ' ,mt.apellido) as medico, cr.numeroTramite
                                FROM `certificados` cr
                                LEFT JOIN enfermedades e on cr.codEnfermedad= e.codEnfermedad
                                LEFT JOIN estados es on cr.idEstado = es.idEstado
                                LEFT JOIN casos c on c.numeroTramite = cr.numeroTramite
                                LEFT JOIN medicos_tratantes mt on mt.matricula = cr.matricula
                                WHERE cr.numeroTramite=".$nroTramite);
        return $sql->getResult();
    }

    public function get_certificados_justificados($nroTramite) {
        $sql = $this->db->query("SELECT cr.idCertificado, e.nombre , cr.fechaEmision, 
        cr.diasOtorgados, cr.lugarReposo, cr.archivo, es.estado, cr.descripcion, CONCAT(mt.nombre, ' ' ,mt.apellido, ' ', mt.matricula) as medicoTratante, cr.numeroTramite 
        FROM `certificados` cr 
        LEFT JOIN enfermedades e on cr.codEnfermedad= e.codEnfermedad 
        LEFT JOIN estados es on cr.idEstado = es.idEstado 
        LEFT JOIN casos c on c.numeroTramite = cr.numeroTramite 
        LEFT JOIN medicos_tratantes mt on mt.matricula = cr.matricula 
        WHERE cr.numeroTramite=".$nroTramite." AND cr.idEstado=5");
        return $sql->getResult();
    }

    public function update_estado_certificado($id, $estado,$razon) {
        return $this->set('idEstado', $estado)
        ->set('descripcion', $razon)
        ->where('idCertificado', $id)
        ->update();
    }

    public function buscarCertificados($nroTramite, $estado){
        return $this->where('numeroTramite', $nroTramite)
        ->where('idEstado', $estado)
        ->findAll();
    }

    public function buscarCertificadosDeGravedad($nroTramite) {
        $sql = $this->db->query("SELECT * FROM `certificados` c INNER JOIN enfermedades e on e.codEnfermedad = c.codEnfermedad 
        LEFT JOIN tipos_categorias tc on tc.idCategoria = e.idCategoria 
        WHERE tc.tiposeveridad!='Simple' AND c.numeroTramite =".$nroTramite);
        return $sql->getResult();
    }

    

    public function getCertificadosPorNumeroTramite($numeroTramite) {
       
        return $this->where('numeroTramite', $numeroTramite)->findAll();
    }

    public function getUltimoCertificado($numeroTramite) {
        return $this->where('numeroTramite', $numeroTramite)->findAll();
    }

    public function obtenerUnCertificado($idCertificado) {
        return $this->join('enfermedades', 'certificados.codEnfermedad = enfermedades.codEnfermedad', 'left')  // LEFT JOIN
        ->where('idCertificado', $idCertificado)->first();
    }

    public function countByMatricula($matricula)
    {
        return $this->where('matricula', $matricula)->countAllResults();
    }
}
