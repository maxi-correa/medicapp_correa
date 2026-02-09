<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\NotificacionModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['inflector_helper','utils_helper','text'];



    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        if (session()->has('rol')) {

            $rol    = session('rol');
            
            $tieneNotificaciones = false;
            
            /* ============================
            EMPLEADO COMÚN
            ============================ */
            if ($rol === 'Empleado Común') {
                
                $legajo = session('legajo');
                $notificacionModel = new NotificacionModel();
                $certificadoModel  = new \App\Models\CertificadoModel();

                // 1️. Notificaciones guardadas en BD
                $cantidadBD = $notificacionModel->contarPorUsuario($legajo, $rol);

                // 2️. Certificados pendientes de revisión
                $cantidadPendientesRevision = $certificadoModel
                    ->join('casos', 'casos.numeroTramite = certificados.numeroTramite')
                    ->where('casos.legajo', $legajo)
                    ->where('certificados.idEstado', 6)
                    ->countAllResults();

                $tieneNotificaciones = ($cantidadBD + $cantidadPendientesRevision) > 0;
            }

            /* ============================
            ADMINISTRADOR
            ============================ */
            elseif ($rol === 'Admin. Medicina Laboral') {

                $certificadoModel = new \App\Models\CertificadoModel();
                $casoModel        = new \App\Models\CasoModel();
                $notificacionModel = new NotificacionModel();

                // 1. Certificados pendientes de revisión
                $cantidadCertificados = $certificadoModel->contarPendientesRevision();

                // 2. Casos activos que requieren sugerir turno
                $cantidadCasosSinTurno = $casoModel
                ->where('idEstado', '2') //ACTIVOS
                ->whereIn('tipoCategoriaVigente', ['2', '3']) //MODERADA O GRAVE
                ->where('fechaSugeridaTurno IS NULL', null, false)
                ->countAllResults();

                // 3. Casos activos sin fecha de sugerencia de turno que están próximos a necesitar reprogramación
                $CasosProximosReprogramacion = $notificacionModel->casosPendientesDeReprogramacion();
                $cantidadCasosProximosReprogramacion = count($CasosProximosReprogramacion);


                $tieneNotificaciones = ($cantidadCertificados + $cantidadCasosSinTurno + $cantidadCasosProximosReprogramacion) > 0;        
            }
            elseif ($rol === 'Medico') 
            {
                $matricula = session('matricula');
                $notificacionModel = new NotificacionModel();

                $turnos = $notificacionModel
                    ->turnosParaNotificacionesPorMedico($matricula);

                // Hay notificación si al menos un turno cumple alguna condición
                $tieneNotificaciones = false;

                foreach ($turnos as $turno) {
                    if (
                        $turno['seguimientoHabilitado'] ||
                        $turno['esRecordatorioPrevioHoy'] ||
                        $turno['esTurnoFuturo']
                    ) {
                        $tieneNotificaciones = true;
                        break;
                    }
                }
            }

        session()->set('tieneNotificaciones', $tieneNotificaciones);
        }
    }

    public function encriptar($msg){
        $encrypter = \Config\Services::encrypter();
        $msg = base64_encode($encrypter->encrypt($msg));
        $msg = str_replace("/", "_",$msg);
        $msg = str_replace("+", "-",$msg);
        return $msg;
    }

    public function desencriptar($msg){
        $encrypter = \Config\Services::encrypter();
        $msg = str_replace("_", "/",$msg);
        $msg = str_replace("-", "+",$msg);
        $msg = $encrypter->decrypt(base64_decode($msg));
        return $msg;
    }
}
