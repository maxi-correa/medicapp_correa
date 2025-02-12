<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class SeguimientoModel extends Model
{
    protected $table            = 'seguimientos';
    protected $primaryKey       = 'idSeguimiento';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idTurno', 'tipoSeguimiento', 'fechaFinOriginalCaso', 'observacion', 'diasParaProximoTurno'];
    #.............................................................
    public function generarIdSeguimiento($idTurno)
    {

        $ultimoSeguimiento = $this->where('idTurno', $idTurno)
            ->orderBy('idSeguimiento', 'desc')
            ->first();
        return $ultimoSeguimiento ? $ultimoSeguimiento['idSeguimiento'] + 1 : 1;
    }

    public function obtenerValoresEnum($columna)
    {
        $query = $this->db->query("SHOW COLUMNS FROM {$this->table} LIKE ?", [$columna]);
        $enumerado = $query->getRow();

        if ($enumerado) {
            preg_match("/^enum\((.*)\)$/", $enumerado->Type, $matches);
            if (!empty($matches[1])) {
                $enumValor = str_getcsv($matches[1], ',', "'");
                return $enumValor;
            }
        }
        return [];
    }

    public function obtenerDiasParaProximoTurno($nroTramite)
    {
        $sql = $this->db->query("SELECT s.diasParaProximoTurno 
                                FROM `seguimientos` s
                                INNER JOIN turnos t on t.idTurno = s.idTurno
                                WHERE t.numeroTramite = " . $nroTramite . "
                                ORDER BY s.diasParaProximoTurno DESC
                                LIMIT 1;");

        return $sql->getResult();
    }

    public function obtenerTurnosConAltaPorTramite($numeroTramite)
    {
        return $this->db->table('turnos')
                    ->select('turnos.*')
                    ->join('seguimientos', 'turnos.idTurno = seguimientos.idTurno')
                    ->where('turnos.numeroTramite', $numeroTramite)
                    ->where('seguimientos.tipoSeguimiento', 'ALTA')
                    ->limit(1)
                    ->get()
                    ->getRow();
    }
}
