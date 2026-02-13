<?php

namespace App\Services;
//Este servicio centraliza la lógica de notificaciones para cada rol, permitiendo que el controlador BaseController solo se encargue de llamar a este servicio sin tener que conocer los detalles de cómo se obtienen las notificaciones.

use App\Models\NotificacionModel;
use App\Models\CertificadoModel;
use App\Models\CasoModel;
use Carbon\Carbon;

class NotificacionService
{

    protected NotificacionModel $notificacionModel;
    protected CertificadoModel $certificadoModel;
    protected CasoModel $casoModel;

    public function __construct()
    {
        $this->notificacionModel = new NotificacionModel();
        $this->certificadoModel  = new CertificadoModel();
        $this->casoModel         = new CasoModel();
    }

    /* ======================================================
       MÉTODO PRINCIPAL
    ====================================================== */

    public function obtenerNotificaciones(string $rol): array
    {
        return match ($rol) {
            'Empleado Común' => $this->notificacionesEmpleado(),
            'Admin. Medicina Laboral' => $this->notificacionesAdmin(),
            'Medico' => $this->notificacionesMedico(),
            default => [], // Si el rol no coincide con ninguno conocido, devolvemos un array vacío
        };
    }

    public function tieneNotificaciones(string $rol): bool
    {
        return count($this->obtenerNotificaciones($rol)) > 0;
    }

    /* ======================================================
       EMPLEADO
    ====================================================== */

    private function notificacionesEmpleado(): array
    {
        $notificaciones = [];
        $legajo = session('legajo');

        $pendientes = $this->notificacionModel->certificadosPendientesDeCarga($legajo);

        foreach ($pendientes as $caso) {

            $fechaLimite = date('d/m/Y', strtotime($caso['fechaFin']));

            $notificaciones[] = [
                'fecha'   => $caso['fechaFin'],
                'tipo'    => 'Certificado pendiente',
                'mensaje' => "Tiene hasta el {$fechaLimite} para presentar su certificado.",
                'link'    => base_url('/visualizarCasoE'),
            ];
        }

        $enRevision = $this->notificacionModel->certificadosEnRevision($legajo);

        foreach ($enRevision as $cert) {

            $notificaciones[] = [
                'fecha'   => $cert['fechaEmision'],
                'tipo'    => 'Certificado en revisión',
                'mensaje' => 'Su certificado fue presentado y se encuentra pendiente de revisión por el área de Administración.',
                'link'    => base_url('/visualizarCasoE'),
            ];
        }

        return $notificaciones;
    }

    /* ======================================================
       ADMIN
    ====================================================== */

    private function notificacionesAdmin(): array
    {
        $notificaciones = [];

        $certificados = $this->notificacionModel->certificadosPendientesRevisionAdmin();

        foreach ($certificados as $cert) {

            $notificaciones[] = [
                'fecha'   => $cert['fechaEmision'],
                'tipo'    => 'Certificado pendiente de revisión',
                'mensaje' => "El certificado para el empleado {$cert['nombre']} {$cert['apellido']} con legajo {$cert['legajo']} se encuentra pendiente de revisión.",
                'link'    => base_url('guardarNumeroTramite/' . $cert['numeroTramite']),
            ];
        }

        $casosSinTurno = $this->notificacionModel->casosActivosSinTurno();

        foreach ($casosSinTurno as $caso) {

            $notificaciones[] = [
                'fecha'   => $caso['fechaInicio'],
                'tipo'    => 'Caso sin turno asignado',
                'mensaje' => "Se le debe registrar un turno al caso del empleado {$caso['nombre']} {$caso['apellido']} con legajo {$caso['legajo']}.",
                'link'    => base_url('guardarNumeroTramite/' . $caso['numeroTramite']),
            ];
        }

        $casosReprogramacion = $this->notificacionModel->casosPendientesDeReprogramacion();

        foreach ($casosReprogramacion as $caso) {

            $notificaciones[] = [
                'fecha'   => date('Y-m-d'),
                'tipo'    => 'Caso próximo a requerir reprogramación',
                'mensaje' => "Al caso del empleado {$caso['nombre']} {$caso['apellido']} con legajo {$caso['legajo']} se le debe reprogramar el turno.",
                'link'    => base_url('guardarNumeroTramite/' . $caso['numeroTramite']),
            ];
        }

        return $notificaciones;
    }

    /* ======================================================
       MÉDICO
    ====================================================== */

    private function notificacionesMedico(): array
    {
        $notificaciones = [];
        $matricula = session('matricula');
        $ahora = Carbon::now();

        $turnos = $this->notificacionModel->turnosParaNotificacionesPorMedico($matricula);

        foreach ($turnos as $turno) {

            if ($turno['seguimientoHabilitado']) {

                $notificaciones[] = [
                    'fecha'   => $ahora->format('Y-m-d H:i:s'),
                    'tipo'    => 'Seguimiento habilitado',
                    'mensaje' => "Se encuentra habilitado el registro de seguimiento del turno de hoy a las {$turno['fechaHoraTurno']->format('H:i')} en {$turno['lugar']} correspondiente al empleado {$turno['nombre']} {$turno['apellido']} (Legajo {$turno['legajo']}).",
                    'link'    => base_url('guardarIdTurno/' . $turno['idTurno']),
                ];
                continue;
            }

            if ($turno['esTurnoHoy'] && $turno['esRecordatorioPrevioHoy']) {

                $notificaciones[] = [
                    'fecha'   => $ahora->format('Y-m-d H:i:s'),
                    'tipo'    => 'Turno programado para HOY',
                    'mensaje' => "Recuerde que tiene un turno programado hoy a las {$turno['fechaHoraTurno']->format('H:i')} en {$turno['lugar']} con el empleado {$turno['nombre']} {$turno['apellido']} (Legajo {$turno['legajo']}).",
                    'link'    => base_url('/turnos/listar'),
                ];
                continue;
            }

            if ($turno['esTurnoFuturo'] && !$turno['esTurnoHoy']) {

                $fecha = $turno['fechaHoraTurno']->format('d/m/Y');
                $hora  = $turno['fechaHoraTurno']->format('H:i');

                $notificaciones[] = [
                    'fecha'   => $turno['fecha'],
                    'tipo'    => 'Recordatorio de próximo turno',
                    'mensaje' => "Recuerde que tiene un turno programado el {$fecha} a las {$hora} en {$turno['lugar']} para atender al empleado {$turno['nombre']} {$turno['apellido']} (Legajo {$turno['legajo']}), con motivo de {$turno['motivo']}.",
                    'link'    => base_url('/turnos/listar'),
                ];
            }
        }

        return $notificaciones;
    }
}
