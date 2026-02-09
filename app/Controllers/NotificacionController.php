<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificacionModel;
use App\Models\CasoModel;
use App\Models\CertificadoModel;
use Carbon\Carbon;


class NotificacionController extends BaseController
{
    protected $casoModel;
    protected $session;
    protected $certificadoModel;
    protected $turnoModel;
    protected $estadoModel;
    protected $empleadoModel;
    protected $familiarModel;
    protected $seguimientoModel;
    protected $notificacionModel;

    public function __construct()
        {
            $this->casoModel = new CasoModel();
            $this->certificadoModel = new CertificadoModel();
            $this->notificacionModel = new NotificacionModel();
            Carbon::setLocale('es');
        }
    
    /**
     * Función de prueba
     * Devuelve todas las notificaciones (si existe la tabla)
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) 
        {
            return redirect()->to('/');
        }   
        
        $notificacionModel = new NotificacionModel();

        $notificaciones = $notificacionModel->obtenerTodas();

        // Por ahora, devolvemos JSON para debug
        return $this->response->setJSON($notificaciones);
    }

    public function cargarEstadoNotificaciones()
    {
        $notificacionModel = new NotificacionModel();

        $legajo = session('legajo');
        $rol    = session('rol');

        $cantidad = $notificacionModel->contarPorUsuario($legajo, $rol);

        session()->set('tieneNotificaciones', $cantidad > 0);
    }

/* ======================================
    Toda la lógica de NOTIFICACIONES está aquí
    ====================================== */

    public function listar()
    {
    $rol    = session('rol');
    
    $notificaciones = [];
    
    /* ======================================
    EMPLEADO (BD notificaciones)
    ====================================== */
    if ($rol === 'Empleado Común') {
        
        $legajo = session('legajo');
        
        $notificacionesDB = $this->notificacionModel
            ->where('estado', 'PENDIENTE')
            ->where('legajo', $legajo)
            ->orderBy('fechaEvento', 'DESC')
            ->findAll();

        $certificadosPendientes = $this->certificadoModel
            ->select('
                certificados.numeroTramite,
                certificados.fechaEmision
            ')
            ->join('casos', 'casos.numeroTramite = certificados.numeroTramite')
            ->where('casos.legajo', $legajo)
            ->where('certificados.idEstado', 6)
            ->findAll();

        foreach ($notificacionesDB as $n) {

            switch ($n['tipo']) {

                case 'CERTIFICADO_PENDIENTE':

                    $caso = $this->casoModel
                        ->where('numeroTramite', $n['numeroTramite'])
                        ->first();

                    $fechaLimite = $caso
                        ? date('d/m/Y', strtotime($caso['fechaFin']))
                        : '—';

                    $notificaciones[] = [
                        'fecha'   => $n['fechaEvento'],
                        'tipo'    => 'Certificado pendiente',
                        'mensaje' => "Tiene hasta el {$fechaLimite} para presentar su certificado.",
                        'link'    => base_url('/visualizarCasoE')
                    ];
                    break;

                case 'TURNO_ASIGNADO':

                    $notificaciones[] = [
                        'fecha'   => $n['fechaEvento'],
                        'tipo'    => 'Turno asignado',
                        'mensaje' => 'Tiene un nuevo turno asignado.',
                        'link'    => base_url('turnos')
                    ];
                    break;
            }
        }
        
        foreach ($certificadosPendientes as $cert) {

        $notificaciones[] = [
            'fecha'   => $cert['fechaEmision'],
            'tipo'    => 'Certificado en revisión',
            'mensaje' => 'Su certificado fue presentado y se encuentra pendiente de revisión por el área de Administración.',
            'link'    => base_url('/visualizarCasoE'),
        ];
        }
    }

    /* ======================================
       ADMINISTRADOR (derivado del sistema)
    ====================================== */
    if ($rol === 'Admin. Medicina Laboral') {

        $legajo = session('legajo');

        /* ======================================
        1. Certificados pendientes de revisión
        ====================================== */

        $certificados = $this->certificadoModel
            ->select('
                certificados.idCertificado,
                certificados.numeroTramite,
                certificados.fechaEmision,
                casos.legajo,
                empleados_rrhh.nombre,
                empleados_rrhh.apellido,
                empleados_rrhh.legajo
            ')
            ->join('casos', 'casos.numeroTramite = certificados.numeroTramite')
            ->join('empleados_rrhh', 'empleados_rrhh.legajo = casos.legajo')
            ->where('certificados.idEstado', 6)
            ->findAll();

        foreach ($certificados as $cert) {

            $notificaciones[] = [
                'fecha'   => $cert['fechaEmision'],
                'tipo'    => 'Certificado pendiente de revisión',
                'mensaje' => "El certificado para el empleado {$cert['nombre']} {$cert['apellido']} con legajo {$cert['legajo']} se encuentra pendiente de revisión.",
                'link'    => base_url('guardarNumeroTramite/' . $cert['numeroTramite']),
            ];
        }

        /* ======================================
        2. Casos activos sin fecha de sugerencia de turno
        ====================================== */

        $casosSinTurno = $this->casoModel
        ->select('
        casos.numeroTramite,
        casos.fechaInicio,
        empleados_rrhh.nombre,
        empleados_rrhh.apellido,
        empleados_rrhh.legajo
        ')
        ->join('empleados_rrhh', 'empleados_rrhh.legajo = casos.legajo')
        ->where('casos.idEstado', '2') //ACTIVOS
        ->whereIn('casos.tipoCategoriaVigente', ['2', '3']) //MODERADA O GRAVE
        ->where('casos.fechaSugeridaTurno IS NULL', null, false)
        ->findAll();

        foreach ($casosSinTurno as $caso) {

            $notificaciones[] = [
                'fecha'   => $caso['fechaInicio'],
                'tipo'    => 'Caso sin turno asignado',
                'mensaje' => "Se le debe registrar un turno al caso del empleado {$caso['nombre']} {$caso['apellido']} con legajo {$caso['legajo']}.",
                'link'    => base_url('guardarNumeroTramite/' . $caso['numeroTramite']),
            ];
        }

        /* ======================================
        3. Casos activos con PROXIMO TURNO que requieren reprogramación
        ====================================== */
        $casosReprogramacion = $this->notificacionModel->casosPendientesDeReprogramacion();
        foreach ($casosReprogramacion as $caso) {

            $notificaciones[] = [
                'fecha'   => date('Y-m-d'), // o la fecha del último seguimiento
                'tipo'    => 'Caso próximo a requerir reprogramación',
                'mensaje' => "Al caso del empleado {$caso['nombre']} {$caso['apellido']} con legajo {$caso['legajo']} se le debe reprogramar el turno.",
                'link'    => base_url('guardarNumeroTramite/' . $caso['numeroTramite']),
            ];
        }
    }

    /* ======================================
       MÉDICO (derivado del sistema)
    ====================================== */

    if ($rol === 'Medico'){
        
        $matricula = session('matricula');
        $ahora     = Carbon::now();

        $turnos = $this->notificacionModel->turnosParaNotificacionesPorMedico($matricula);

        foreach ($turnos as $turno) {

            /* ============================
            1. Seguimiento habilitado
            ============================ */
            if ($turno['seguimientoHabilitado']) {

                $notificaciones[] = [
                    'fecha'   => $ahora->format('Y-m-d H:i:s'),
                    'tipo'    => 'Seguimiento habilitado',
                    'mensaje' =>"Se encuentra habilitado el registro de seguimiento del turno de hoy a las {$turno['fechaHoraTurno']->format('H:i')} en {$turno['lugar']} correspondiente al empleado {$turno['nombre']} {$turno['apellido']} (Legajo {$turno['legajo']}).",
                    'link'    => base_url('guardarIdTurno/' . $turno['idTurno'])
                ];
                continue;
            }

            /* ============================
            2. Turno programado para hoy
            ============================ */
            if ($turno['esTurnoHoy'] && $turno['esRecordatorioPrevioHoy']) {

                $notificaciones[] = [
                    'fecha'   => $ahora->format('Y-m-d H:i:s'),
                    'tipo'    => 'Turno programado para HOY',
                    'mensaje' =>"Recuerde que tiene un turno programado hoy a las {$turno['fechaHoraTurno']->format('H:i')} en {$turno['lugar']} con el empleado {$turno['nombre']} {$turno['apellido']} (Legajo {$turno['legajo']}).",
                    'link'    => base_url('/turnos/listar')
                ];
                continue;
            }

            /* ============================
            3. Próximo turno
            ============================ */
            if ($turno['esTurnoFuturo'] && !$turno['esTurnoHoy']) {

                $fecha = $turno['fechaHoraTurno']->format('d/m/Y');
                $hora  = $turno['fechaHoraTurno']->format('H:i');

                $notificaciones[] = [
                    'fecha'   => $turno['fecha'],
                    'tipo'    => 'Recordatorio de próximo turno',
                    'mensaje' =>"Recuerde que tiene un turno programado el {$fecha} a las {$hora} en {$turno['lugar']} para atender al empleado {$turno['nombre']} {$turno['apellido']} (Legajo {$turno['legajo']}), con motivo de {$turno['motivo']}.",
                    'link'    => base_url('/turnos/listar')
                ];
            }
        }    
    }
    return view('notificaciones/notificaciones', [
        'notificaciones' => $notificaciones
    ]);
}
}
