<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CasoModel;
use App\Models\CertificadoModel;
use App\Models\TurnoModel;
use App\Models\EstadoModel;
use Carbon\Carbon;
use App\Models\EmpleadoModel;
use App\Models\FamiliarModel;
use App\Models\SeguimientoModel;

class CasoController extends BaseController
{
    protected $casoModel;
    protected $session;
    protected $certificadoModel;
    protected $turnoModel;
    protected $estadoModel;
    protected $empleadoModel;
    protected $familiarModel;
    protected $seguimientoModel;

    public function __construct()
    {
        $this->casoModel = new CasoModel();
        $this->certificadoModel = new CertificadoModel();
        $this->turnoModel = new TurnoModel();
        $this->estadoModel = new EstadoModel();
        $this->empleadoModel = new EmpleadoModel();
        $this->familiarModel = new FamiliarModel();
        $this->seguimientoModel = new SeguimientoModel();
        Carbon::setLocale('es');
    }


    //LISTAR TODOS LOS CASOS
    public function index()
    {   
        $this->verificarCasos();
        $datos['casosFinalizado'] = $this->casoModel->get_casos_finalizados();
        $datos['casosPendientes'] = $this->casoModel->get_casos_pendientes();
        $datos['casosActivos'] = $this->casoModel->get_casos_activos();
        // $this->seguimientoModel => obtenerDiasParaProximoTurno($nroTramite);

        $datosTablaPendiente = [
            'estado' => 'PENDIENTE',
            'idTabla' => 'tablePendientes',
            'url'     => 'assets/js/tablas/listarCasosPendientes.js',
            'idBody'  =>  'tableBodyPendiente',
            'headers' => array(
                'NRO TRAMITE',
                'LEGAJO',
                'RAZON DEL CERTIFICADO',
                'FECHA DE AUSENCIA',
                'NOMBRE',
                'APELLIDO',
                'DISPONE DE CERTIFICADO'
            )
        ];

        $datosTablaActivos = [
            'estado' => 'ACTIVOS',
            'idTabla' => 'tableActivos',
            'url'     => 'assets/js/tablas/listarCasosActivos.js',
            'idBody'  =>  'tableBodyActivos',
            'headers' => array(
                'NRO TRAMITE',
                'LEGAJO',
                'RAZON DEL CERTIFICADO',
                'FECHA DE AUSENCIA',
                'NOMBRE',
                'APELLIDO',
                'IS',
                'SEVERIDAD',
                'TURNO SUGERIDO'
            )
        ];

        $datosTablaFinalizado = [
            'estado' => 'FINALIZADO',
            'idTabla' => 'tableFinalizados',
            'url'     => 'assets/js/tablas/listarCasosFinalizados.js',
            'idBody'  =>  'tableBodyFinalizado',
            'headers' => array(
                'NRO TRAMITE',
                'LEGAJO',
                'RAZON DEL CERTIFICADO',
                'FECHA DE AUSENCIA',
                'NOMBRE',
                'APELLIDO',
                'DISPONE DE CERTIFICADO'
            )
        ];

        echo view('administrador/listarCasos', $datos)
            . view('templates/tablaListarCasos', $datosTablaPendiente)
            . view('templates/tablaListarCasos', $datosTablaActivos)
            . view('templates/tablaListarCasos', $datosTablaFinalizado)
            .  view('templates/footer');
    }


    public function mostrarUnCasoAdmin()
    {
        $numeroTramite = session()->get('numeroTramite');

        //DATOS DEL EMPLEADO
        $datos['datosEmpleado'] = $this->casoModel->obtenerUnCaso($numeroTramite);
        $datos['datosEmpleado'] = $this->casoModel->obtenerUnCaso($numeroTramite);

        $datos['lugarCertificado'] = $this->casoModel->get_lugar_certificado($numeroTramite);
        $datos['datosCertificados'] = $this->certificadoModel->get_certificados($numeroTramite);
        $datos['datosSeguimientos'] = $this->turnoModel->obtenerTurnoSeguimiento($numeroTramite);
        $datos['estadosCertificados'] = $this->estadoModel->get_estados_certificados();


        $tieneCertificadosModeradosComplejos = $this->certificadoModel->buscarCertificadosDeGravedad($numeroTramite);
        if (empty($tieneCertificadosModeradosComplejos)) {
            $datos['display'] = false; //NO MUESTRO EL BOTON
        } else {
            $datos['display'] = true; //MUESTRO EL BOTON
        }

        $tieneTurnoActivos = $this->turnoModel->buscarTurnosActivos($numeroTramite);
        if (empty($tieneTurnoActivos)) {
            $datos['displayTurnosActivo'] = true; //MUESTRO EL BOTON
        } else {
            $datos['displayTurnosActivo'] = false; //NO MUESTRO EL BOTON
        }

        echo view('administrador/unCaso', $datos);
    }

    public function mostrarUnCasoEmpleado()
    {

        $numeroTramite = session()->get('numeroTramite');

        $datos['datosEmpleado'] = $this->casoModel->obtenerUnCaso($numeroTramite);
        $datos['lugarCertificado'] = $this->casoModel->get_lugar_certificado($numeroTramite);
        $datos['datosCertificados'] = $this->certificadoModel->get_certificados($numeroTramite);
        $datos['datosSeguimientos'] = $this->turnoModel->obtenerTurnoSeguimiento($numeroTramite);

        $tieneTurnosPendientes = $this->turnoModel->obtenerTurnosPendientes($numeroTramite);
        $turnoConAlta = $this->seguimientoModel->obtenerTurnosConAltaPorTramite($numeroTramite);

        /* if (empty($tieneTurnosPendientes)) {
            $datos['display'] = true; //NO MUESTRO EL BOTON
        } else {
            $datos['display'] = false; //MUESTRO EL BOTON
        }
        $datos['display'] = !isset($turnoConAlta); */
        
        $certificado = $this->certificadoModel->where('numeroTramite', $numeroTramite)->findAll();
        if (empty($certificado)) {
            $datos['display'] = true; //NO MUESTRO EL BOTON
        } else {
            $datos['display'] = false; //MUESTRO EL BOTON
        }

        echo view('empleado/unCaso', $datos);
    }

    public function mostrarUnCasoMedico()
    {
        $numeroTramite = session()->get('numeroTramite');

        $datos['datosEmpleado'] = $this->casoModel->obtenerUnCaso($numeroTramite);
        $datos['lugarCertificado'] = $this->casoModel->get_lugar_certificado($numeroTramite);
        $datos['datosCertificados'] = $this->certificadoModel->get_certificados_justificados($numeroTramite);
        $datos['datosSeguimientos'] = $this->turnoModel->obtenerTurnoSeguimiento($numeroTramite);

        echo view('medicoAuditor/unCaso', $datos);
    }


    public function verificarCasos()
    {
        $fechaActual = Carbon::now();

        $casosActivos = $this->casoModel
            ->where('idEstado', '2') //ACTIVOS
            ->findAll();

        foreach ($casosActivos as $caso) {
            $fechaFin = Carbon::parse($caso['fechaFin']);

            if ($fechaFin <= $fechaActual) {
                $this->casoModel->update($caso['numeroTramite'], ['idEstado' => '3']); //FINALIZADOS
            }
        }
    }

    public function registrarCaso()
    {
        $session = session();
        $legajo = $session->get('legajo');
        $casoActivo = $this->casoModel->where('legajo', $legajo)
            ->where('idEstado', '2')
            ->first();

        $casoPendiente = $this->casoModel->where('legajo', $legajo)
            ->where('idEstado', '1')
            ->first();
        if ($casoActivo || $casoPendiente) {
            return redirect()->to('/menu-empleado');
        } else {
            $familia = $this->familiarModel->obtenerFamiliaresDeEmpleado($legajo);
            return view('caso/registrar-caso', ['familiares' => $familia]);
        }
    }


    public function validar()
    {
        $session = session();
        $legajo  =  $session->get('legajo');
        $fechaInicio = $this->request->getPost('fechaAusencia');
        $fechaInicioCarbon = Carbon::parse($fechaInicio);
        $fechaFinCarbon = $fechaInicioCarbon->addDays(2);

        $data = [
            'fechaInicio' => $this->request->getPost('fechaAusencia'),
            'fechaFin' => $fechaFinCarbon,
            'motivo' => $this->request->getPost('motivo'),
            'corresponde' => $this->request->getPost('corresponde'),
            'legajo' => $legajo,
            'idFamiliar' => $this->request->getPost('familiar') ?? null,
            'idEstado' => 1, //CASO PENDIENTE
        ];
        $caso = $this->casoModel->insert($data);
        $numeroTramite = $this->casoModel->insertID();
        $session->set('numeroTramite', $numeroTramite);
        if ($caso) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'El caso se registró correctamente.',
                'numeroTramite' => $numeroTramite,
            ]);
        }
    }

    public function guardarNumeroTramite($numeroTramite)
    {
        $rolInicioSesion = session()->get('rol');
        session()->set('numeroTramite', $numeroTramite);

        if ($rolInicioSesion == "Admin. Medicina Laboral") {
            return redirect()->to('visualizarCasoA');
        }

        if ($rolInicioSesion == "Medico") {
            return redirect()->to('visualizarCasoM');
        }
    }

    
    public function historial()
    {
        $session = session();
        $legajo = $session->get('legajo');
        $casosPendientes = $this->casoModel->get_casos_pendientes_empleado($legajo);
        $casosActivos = $this->casoModel->get_casos_activos_empleado($legajo);
        $casosFinalizados = $this->casoModel->get_casos_finalizados_empleado($legajo);
        $nombre = $session->get('nombre');
        $apellido = $session->get('apellido');

        return view('empleado/historialCasos',['nombre'=>$nombre, 'apellido'=>$apellido, 'legajo'=>$legajo,'casosPendientes'=>$casosPendientes, 'casosActivos'=>$casosActivos, 'casosFinalizados'=>$casosFinalizados]);
    }
}