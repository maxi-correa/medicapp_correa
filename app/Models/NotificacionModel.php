<?php

namespace App\Models;

use CodeIgniter\Model;
use Carbon\Carbon;

class NotificacionModel extends Model
{
    protected $table            = 'notificaciones';
    protected $primaryKey       = 'idNotificacion';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'tipo',
        'rolDestino',
        'legajo',
        'matricula',
        'idTurno',
        'idCertificado',
        'numeroTramite',
        'fechaEvento',
        'estado',
    ];

    protected $useTimestamps = false;
    
    // Función solo de prueba
    public function obtenerTodas()
    {
        return $this->findAll();
    }
    
    // Función para contar notificaciones por usuario y rol, de prueba
    public function contarPorUsuario($legajo, $rol)
    {
    return $this->where('estado', 'PENDIENTE')
                ->where('legajo', $legajo)
                ->where('rolDestino', $rol)
                ->countAllResults();
    }

    /*=================
    EMPLEADO
    =================*/
    
    public function certificadosPendientesDeCarga(string $legajo): array
    {
        return $this->db->table('casos c')
            ->select('
                c.numeroTramite,
                c.fechaFin,
                e.nombre,
                e.apellido
            ')
            ->join('empleados_rrhh e', 'e.legajo = c.legajo')
            ->join('certificados cert', 'cert.numeroTramite = c.numeroTramite', 'left')
            ->where('c.legajo', $legajo)
            ->where('c.idEstado', 1) // PENDIENTE
            ->where('cert.idCertificado IS NULL')
            ->get()
            ->getResultArray();
    }

    public function certificadosEnRevision(string $legajo): array
    {
        return $this->db->table('certificados cert')
            ->select('
                cert.numeroTramite,
                cert.fechaEmision,
                e.nombre,
                e.apellido
            ')
            ->join('casos c', 'c.numeroTramite = cert.numeroTramite')
            ->join('empleados_rrhh e', 'e.legajo = c.legajo')
            ->where('c.legajo', $legajo)
            ->where('cert.idEstado', 6) // PENDIENTE DE REVISION
            ->get()
            ->getResultArray();
    }

    //Función de empleado especifica para BaseController
    public function contarNotificacionesEmpleado(string $legajo): int
    {
        // 1️. Certificados pendientes de carga
        $pendientes = $this->db->table('casos c')
            ->join('certificados cert', 'cert.numeroTramite = c.numeroTramite', 'left')
            ->where('c.legajo', $legajo)
            ->where('c.idEstado', 1)
            ->where('cert.idCertificado IS NULL')
            ->countAllResults();

        // 2️. Certificados en revisión
        $enRevision = $this->db->table('certificados cert')
            ->join('casos c', 'c.numeroTramite = cert.numeroTramite')
            ->where('c.legajo', $legajo)
            ->where('cert.idEstado', 6)
            ->countAllResults();

        return $pendientes + $enRevision;
    }

    /*=================
    AMINISTRADOR
    =================*/

    public function certificadosPendientesRevisionAdmin(): array
    {
        return $this->db->table('certificados cert')
            ->select('
                cert.idCertificado,
                cert.numeroTramite,
                cert.fechaEmision,
                e.nombre,
                e.apellido,
                e.legajo
            ')
            ->join('casos c', 'c.numeroTramite = cert.numeroTramite')
            ->join('empleados_rrhh e', 'e.legajo = c.legajo')
            ->where('cert.idEstado', 6)
            ->get()
            ->getResultArray();
    }

    public function casosActivosSinTurno(): array
    {
        return $this->db->table('casos c')
            ->select('
                c.numeroTramite,
                c.fechaInicio,
                e.nombre,
                e.apellido,
                e.legajo
            ')
            ->join('empleados_rrhh e', 'e.legajo = c.legajo')
            ->join('turnos t', 't.numeroTramite = c.numeroTramite', 'left')
            ->where('c.idEstado', 2) // ACTIVO
            ->whereIn('c.tipoCategoriaVigente', [2, 3]) // MODERADA o GRAVE
            ->where('t.idTurno IS NULL', null, false) // sin turnos asociados
            ->get()
            ->getResultArray();
    }

    public function resolverCertificadoPendiente($legajo, $numeroTramite)
    {
        return $this->where('tipo', 'CERTIFICADO_PENDIENTE')
                    ->where('legajo', $legajo)
                    ->where('numeroTramite', $numeroTramite)
                    ->where('estado', 'PENDIENTE')
                    ->set('estado', 'RESUELTA')
                    ->update();
    }

    // Función para ADMIN para obtener casos activos sin turno sugerido que están próximos a necesitar reprogramación
    public function casosPendientesDeReprogramacion(): array
    {
        $hoy = Carbon::now()->toDateString();

        $builder = $this->db->table('casos c');

        $builder->select('
            e.nombre,
            e.apellido,
            c.legajo,
            c.numeroTramite,
            s.idTurno,
            s.diasParaProximoTurno
        ');

        $builder->join('empleados_rrhh e', 'e.legajo = c.legajo');
        $builder->join('turnos t', 't.numeroTramite = c.numeroTramite');

        $builder->join(
            'seguimientos s',
            's.idTurno = t.idTurno
            AND s.idSeguimiento = (
                SELECT MAX(s2.idSeguimiento)
                FROM seguimientos s2
                WHERE s2.idTurno = t.idTurno
            )',
            'inner',
            false
        );

        $builder->where('c.idEstado', 2); // activos

        $builder->where(
            't.idTurno = (
                SELECT MAX(t2.idTurno)
                FROM turnos t2
                WHERE t2.numeroTramite = c.numeroTramite
            )',
            null,
            false
        );

        $builder->where('s.tipoSeguimiento', 'PROXIMO TURNO');
        $builder->where('s.diasParaProximoTurno >=', $hoy);

        return $builder->get()->getResultArray();
    }


    public function turnosPendientesDeAtencionPorMedico(string $matricula): array
    {
        $hoy = Carbon::now()->toDateString();

        $builder = $this->db->table('turnos t');

        $builder->select('
            t.idTurno,
            t.fecha,
            t.hora,
            t.lugar,
            t.motivo,
            c.legajo,
            e.nombre,
            e.apellido
        ');

        $builder->join('casos c', 'c.numeroTramite = t.numeroTramite');
        $builder->join('empleados_rrhh e', 'e.legajo = c.legajo');

        $builder->where('t.matricula', $matricula);
        $builder->where('t.fecha >', $hoy);
        
        // LEFT JOIN para detectar ausencia de seguimientos
        $builder->join(
            'seguimientos s',
            's.idTurno = t.idTurno',
            'left'
        );

        $builder->where('s.idTurno IS NULL', null, false); // sin turnos registrados

        return $builder->get()->getResultArray();
    }

    public function turnosDisponiblesParaSeguimientoPorMedico(string $matricula): array
    {
        $ahora = Carbon::now();

        $builder = $this->db->table('turnos t');

        $builder->select('
            t.idTurno,
            t.numeroTramite,
            t.fecha,
            t.hora,
            t.lugar,
            c.legajo,
            e.nombre,
            e.apellido
        ');

        $builder->join('casos c', 'c.numeroTramite = t.numeroTramite');
        $builder->join('empleados_rrhh e', 'e.legajo = c.legajo');

        // Solo turnos del médico
        $builder->where('t.matricula', $matricula);

        // Turnos de HOY
        $builder->where('t.fecha', $ahora->toDateString());

        // Excluimos turnos con seguimiento ALTA o IRREGULAR
        $builder->whereNotIn('t.idTurno', function ($sub) {
            $sub->select('idTurno')
                ->from('seguimientos')
                ->whereIn('tipoSeguimiento', ['ALTA', 'IRREGULAR']);
        });

        $turnos = $builder->get()->getResultArray();

        // Filtro horario en PHP (para no usar SQL)
        return array_filter($turnos, function ($turno) use ($ahora) {

            $fechaTurno = Carbon::parse($turno['fecha']);
            $horaTurno  = Carbon::parse($turno['hora']);

            $fechaHoraTurno = $fechaTurno->setTime($horaTurno->hour, $horaTurno->minute, 0);

            $inicio = $fechaHoraTurno->copy()->subHours(6); // copy para no modificar $fechaHoraTurno
            $fin    = $fechaHoraTurno->copy()->addHours(6);

            return $ahora->between($inicio, $fin);
        });
    }

    /*=================
    MÉDICO AUDITOR
    =================*/

    public function turnosParaNotificacionesPorMedico(string $matricula): array
    {
        $ahora = Carbon::now();

        $builder = $this->db->table('turnos t');

        $builder->select('
            t.idTurno,
            t.numeroTramite,
            t.fecha,
            t.hora,
            t.lugar,
            t.motivo,
            c.legajo,
            e.nombre,
            e.apellido
        ');

        $builder->join('casos c', 'c.numeroTramite = t.numeroTramite');
        $builder->join('empleados_rrhh e', 'e.legajo = c.legajo');

        $builder->where('t.matricula', $matricula);

        // Hoy en adelante
        $builder->where('t.fecha >=', $ahora->toDateString());

        // Excluir turnos que ya tengan seguimiento
        $builder->join('seguimientos s', 's.idTurno = t.idTurno', 'left');
        $builder->where('s.idTurno IS NULL');

        $turnos = $builder->get()->getResultArray();

        return array_map(function ($turno) use ($ahora) {

            $fechaHoraTurno = Carbon::parse($turno['fecha'] . ' ' . $turno['hora']);

            $inicioDia      = $fechaHoraTurno->copy()->startOfDay();
            $inicioVentana  = $fechaHoraTurno->copy()->subHours(6);
            $finVentana     = $fechaHoraTurno->copy()->addHours(6);

            return [
                ...$turno,

                // Fechas calculadas
                'fechaHoraTurno'  => $fechaHoraTurno,
                'inicioDiaTurno'  => $inicioDia,
                'inicioVentana6h' => $inicioVentana,
                'finVentana6h'    => $finVentana,

                // Estados lógicos
                'esTurnoHoy'              => $fechaHoraTurno->isToday(),
                'seguimientoHabilitado'   => $ahora->between($inicioVentana, $finVentana),
                'esRecordatorioPrevioHoy' => $ahora->between($inicioDia, $inicioVentana),
                'esTurnoFuturo'           => $fechaHoraTurno->isAfter($ahora),
            ];

        }, $turnos);
    }
}

