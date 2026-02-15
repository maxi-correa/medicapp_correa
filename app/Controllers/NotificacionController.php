<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificacionModel;
use App\Models\CasoModel;
use App\Models\CertificadoModel;
use App\Services\NotificacionService;
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

    public function listar()
    {
        $rol = session('rol');

        $service = new NotificacionService();

        $notificaciones = $service->obtenerNotificaciones($rol);

        return view('notificaciones/notificaciones', [
            'notificaciones' => $notificaciones
        ]);
    }
}
