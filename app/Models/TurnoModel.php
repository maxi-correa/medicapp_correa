<?php

namespace App\Models;

use CodeIgniter\Model;

class TurnoModel extends Model
{
    protected $table            = 'turnos';
    protected $primaryKey       = 'idTurno';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['fecha','hora','lugar','motivo','numeroTramite','matricula','idEstado'];

    public function get_seguimientos($nroTramite) {
        $sql = $this->db->query("SELECT CONCAT(t.fecha, ' ' ,t.hora) as turno, 
                                CONCAT(ma.nombre, ' ', ma.apellido) as medico, 
                                CONCAT(s.tipoSeguimiento,'-',S.diasParaProximoTurno), 
                                s.observacion as razon 
                                FROM `turnos` t 
                                LEFT JOIN medicos_auditores ma on t.matricula = ma.matricula 
                                LEFT JOIN seguimientos s on t.idSeguimiento = s.idSeguimiento 
                                LEFT JOIN casos c on c.numeroTramite = t.numeroTramite 
                                WHERE t.numeroTramite=". $nroTramite);
        return $sql->getResult();
    }
    public function obtenerUltimoTurno($numeroTramite){
        return $this->where('numeroTramite', $numeroTramite)
                ->orderBy('idTurno', 'DESC')
                ->first();
    }

    public function obtenerTurnosPorDia($fecha)
    {   
        $sql = $this->db->query("SELECT t.idTurno, 
                                t.fecha,
                                CONCAT (t.hora) as hora,
                                CONCAT (erh.nombre,' ',erh.apellido) as paciente,
                                CONCAT (ma.nombre,' ',ma.apellido) as medico, 
                                (ca.lugarReposo) as direccion, e.estado,
                                t.numeroTramite
                                FROM `turnos` as t
                                LEFT JOIN casos as ca on t.numeroTramite = ca.numeroTramite
                                LEFT JOIN empleados_rrhh as erh on ca.legajo = erh.legajo
                                LEFT JOIN medicos_auditores as ma on t.matricula = ma.matricula
                                INNER JOIN estados as e on e.idEstado = t.idEstado
                                WHERE t.fecha = ?", [$fecha]);
        return $sql->getResult();
        /*
        return $this->where('fecha', $fecha)
                    ->findAll(); // Esto obtiene todos los registros para esa fecha */
    }
    /*public function mostrarTurnos(){
        return $this->findAll();
    }*/

    public function obtenerTurnosPorDiaMedic($fecha, $matricula)
    {   
        $sql = $this->db->query("SELECT t.idTurno, 
                                t.fecha,
                                CONCAT (t.hora) as hora,
                                CONCAT (erh.nombre,' ',erh.apellido) as paciente,
                                CONCAT (ma.nombre,' ',ma.apellido) as medico, 
                                (ca.lugarReposo) as direccion,
                                t.motivo,
                                t.numeroTramite
                                FROM `turnos` as t
                                LEFT JOIN casos as ca on t.numeroTramite = ca.numeroTramite
                                LEFT JOIN empleados_rrhh as erh on ca.legajo = erh.legajo
                                LEFT JOIN medicos_auditores as ma on t.matricula = ma.matricula
                                WHERE t.fecha = ? AND t.matricula = ?   AND t.idEstado = 10", [$fecha, $matricula]);
        return $sql->getResult();
        /*
        return $this->where('fecha', $fecha)
                    ->findAll(); // Esto obtiene todos los registros para esa fecha */
    }

    public function mostrarTurnos($fechaInicio){
        $sql = $this->db->query("SELECT t.idTurno, 
                                t.fecha,
                                CONCAT (t.hora) as hora,
                                CONCAT (erh.nombre,' ',erh.apellido) as paciente,
                                CONCAT (ma.nombre,' ',ma.apellido) as medico, 
                                (ca.lugarReposo) as direccion
                                FROM `turnos` as t
                                LEFT JOIN casos as ca on t.numeroTramite = ca.numeroTramite
                                LEFT JOIN empleados_rrhh as erh on ca.legajo = erh.legajo
                                LEFT JOIN medicos_auditores as ma on t.matricula = ma.matricula
                                WHERE t.fecha >= ?
                                AND t.fecha < DATE_ADD(?, INTERVAL 1 WEEK)
                                ORDER BY t.fecha", [$fechaInicio, $fechaInicio]);
        return $sql->getResult();
                                
    }


    public function obtenerTurnosMedico($matricula){
        $fechaHoy = date('Y-m-d');  // Obtener la fecha actual

        return $this->where('matricula', $matricula)
                    ->where('idEstado', 10)
                    ->where('fecha >=', $fechaHoy)  // Filtrar turnos a partir de hoy
                    ->orderBy('fecha', 'DESC')
                    ->findAll();
    }

    public function obtenerTurnosNroTramiteMedico($matricula, $numeroTramite){
        return $this->where('matricula', $matricula)
            ->where('numeroTramite', $numeroTramite)
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }

    public function verSiHayTurnoEnEseHorario($matricula, $fecha, $hora)
    {

        return $this->where('matricula', $matricula)
                    ->where('fecha', $fecha)
                    ->where('hora', $hora)
                    ->first();
        
    }

    public function buscarTurnosActivos($nroTramite) {
        $sql = $this->db->query("SELECT * FROM `turnos` t 
        WHERE t.numeroTramite = ".$nroTramite." AND t.idEstado=10;");
        return $sql->getResult();
    }

    public function obtenerTurnoSeguimiento($numeroTramite)
    {
        $sql = $this->db->query("SELECT t.fecha, t.hora, t.lugar, 
        CONCAT(ma.nombre, ' ', ma.apellido) AS medico, s.observacion, s.tipoSeguimiento 
        FROM `turnos` t INNER JOIN medicos_auditores ma on ma.matricula = t.matricula 
        LEFT JOIN seguimientos s on t.idTurno = s.idTurno 
        WHERE t.numeroTramite =".$numeroTramite);
        return $sql->getResult();
    }

    public function obtenerTurnosPendientes($numeroTramite)
    {
        return $this->where('numeroTramite', $numeroTramite)
                    ->where('idEstado', 10)
                    ->findAll();
    }


    public function obtenerNumeroTramitePorIdTurno($idTurno){
        $query = $this->db->table('turnos')  // Especificamos la tabla 'turnos'
                    ->select('numeroTramite')  // Seleccionamos  columna 'numeroTramite'
                    ->where('idTurno', $idTurno)  // Aplicamos el filtro para 'idTurno'
                    ->get();

    // Si encontramos un resultado, devolvemos el 'numeroTramite'
        $resultado = $query->getRow();
        return $resultado ? $resultado->numeroTramite : null;
    }


    //CANCELAR TURNOS DE UN MEDICO
function cancelarTurnos($matricula)
{
    // Obtenemos la fecha actual
    $fechaActual = date('Y-m-d'); // Si necesitas fecha y hora, usa 'Y-m-d H:i:s'

    // Realizamos la consulta para obtener los turnos asociados a la matrícula
    // y que tengan una fecha posterior o igual a la actual
    $sql = "UPDATE turnos 
            SET idEstado = 7 
            WHERE matricula = '$matricula' 
            AND fecha >= '$fechaActual'"; // Filtramos por matrícula y por fecha actual

    // Ejecutamos la consulta
    $this->db->query($sql, [$matricula, $fechaActual]);

    // Verificamos cuántas filas fueron afectadas (si se actualizaron turnos)
    if ($this->db->affectedRows() > 0) {
        return true; // Si se han actualizado turnos
    } else {
        return false; // Si no se actualizó ningún turno
    }
}


}
