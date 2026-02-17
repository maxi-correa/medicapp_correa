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

        $porPagina = 3;
        $pagina = (int) ($this->request->getGet('page') ?? 1);

        $total = count($notificaciones);
        $totalPaginas = ceil($total / $porPagina);

        // evitar página inválida
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        $inicio = ($pagina - 1) * $porPagina;
        $notificacionesPagina = array_slice($notificaciones, $inicio, $porPagina);

        return view('notificaciones/notificaciones', [
            'notificaciones' => $notificacionesPagina,
            'paginaActual'   => $pagina,
            'totalPaginas'   => $totalPaginas
        ]);
    }
}
