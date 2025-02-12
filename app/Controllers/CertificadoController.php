<?php

namespace App\Controllers;

use App\Models\EnfermedadModel;
use App\Controllers\BaseController;
use App\Models\CasoModel;
use App\Models\CertificadoModel;
use App\Models\EmpleadoCategoriaModel;
use App\Models\MedicoModel;
use App\Models\MedicoTratanteModel;
use App\Models\SeguimientoModel;
use App\Models\TipoCategoriaModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;
use Config\Services;
use Exception;
use DateTime;
use Carbon\Carbon;

class CertificadoController extends BaseController
{
    protected $enfermedadModel;
    protected $medicoTratanteModel;
    protected $medicoSisaModel;
    protected $categoriaEnferemdadModel;
    protected $casoModel;
    protected $certificadoModel;
    protected $empleadoCategoriaModel;
    protected $seguimientoModel;


    public function __construct()
    {
        $this->enfermedadModel = new EnfermedadModel();
        $this->seguimientoModel = new SeguimientoModel();
        $this->medicoTratanteModel = new MedicoTratanteModel();
        $this->medicoSisaModel = new MedicoModel();
        $this->categoriaEnferemdadModel = new TipoCategoriaModel();
        $this->casoModel = new CasoModel();
        $this->certificadoModel = new CertificadoModel();
        $this->empleadoCategoriaModel = new EmpleadoCategoriaModel();

        Carbon::setLocale('es');
    }

    public function formulario()
    {
        $numeroTramite = session()->get('numeroTramite');
        /* $turnoConAlta = $this->seguimientoModel->obtenerTurnosConAltaPorTramite($numeroTramite);
        if (isset($turnoConAlta))
        {
            return redirect()->to('visualizarCasoE');
        } */
        $certificado = $this->certificadoModel->where('numeroTramite', $numeroTramite)->findAll();
        if ($certificado) {
            return redirect()->to('visualizarCasoE');
        }
        $data['enfermedades'] = $this->enfermedadModel->orderBy('nombre', 'ASC')->findAll();
        return view('certificado/subir-certificado', $data);
    }

    public function validar_formulario()
    {
        $encrypter = Services::encrypter();

        $fechaEmision = $this->request->getPost('fechaEmision');
        $codEnfermedad = $this->request->getPost('enfermedad');
        $diasReposo = $this->request->getPost('reposo');
        $matriculaMedico = $this->request->getPost('matricula');
        $direccionReposo = $this->request->getPost('direccion');
        $archivoCertificado = $this->request->getFile('certificado');

        if (!$this->medicoTratanteModel->find($matriculaMedico)) {
            $medico = $this->medicoSisaModel->find($matriculaMedico);
            $data =
                [
                    'matricula' => $medico['matricula'],
                    'nombre' => $medico['nombre'],
                    'apellido' => $medico['apellido'],
                ];
            $this->medicoTratanteModel->insert($data);
        }
        $session = session();
        $legajo = $session->get('legajo');
        $ruta = WRITEPATH . 'certificados/' . $legajo;

        // Si la carpeta con el legajo del empleado no existe la crea
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, false);
        }


        $enfermedad = $this->enfermedadModel->find($codEnfermedad);
        $empleadoCategoria = $this->empleadoCategoriaModel->obtenerEmpleadoCategoria($legajo, $enfermedad['idCategoria']);
        $tipoCategoriaVigente = $this->enfermedadModel->obtenerCategoriaPorEnfermedad($codEnfermedad);

        $numeroTramiteDeCasoActual = $session->get('numeroTramite');
        $diasOtorgadosFecha = $this->calcularFecha($fechaEmision, $diasReposo);
        $casoActual = $this->casoModel->find($numeroTramiteDeCasoActual);
        $certificadosDelCasoActual = $this->certificadoModel->getCertificadosPorNumeroTramite($numeroTramiteDeCasoActual);

        if ($empleadoCategoria['diasDisponibles'] >= $diasReposo) {
            $casoActual['idEstado'] = 1; # CASO PENDIENTEE 
            $casoActual['tipoCategoriaVigente'] = $tipoCategoriaVigente;
            $this->casoModel->update($numeroTramiteDeCasoActual, $casoActual);
            $data =
                [
                    'fechaEmision' => $fechaEmision,
                    'diasOtorgados' => $diasOtorgadosFecha,
                    'lugarReposo' => $direccionReposo,
                    'archivo' => 0,
                    'numeroTramite' => $numeroTramiteDeCasoActual,
                    'matricula' => $matriculaMedico,
                    'codEnfermedad' => $codEnfermedad,
                    'idEstado' => 6, #PENDIENTE DE REVISION CERTIFICADO
                ];
            if ($this->certificadoModel->insert($data)) {
                // Obtiene el ID del último registro insertado
                $nuevoID = $this->certificadoModel->insertID();
                $this->guardarImagen($ruta, $nuevoID, $archivoCertificado);
                return redirect()->to('visualizarCasoE')->with('success', 'Certificado guardado correctamente.');
            }
        } else {
            if (empty($certificadosDelCasoActual)) {
                $casoActual['idEstado'] = 3; # CASO FINALIZADOO // si solo hay un certificado
                $this->casoModel->update($numeroTramiteDeCasoActual, $casoActual);
                $data =
                    [
                        'fechaEmision' => $fechaEmision,
                        'diasOtorgados' => $diasOtorgadosFecha,
                        'lugarReposo' => $direccionReposo,
                        'archivo' => 0,
                        'numeroTramite' => $numeroTramiteDeCasoActual,
                        'matricula' => $matriculaMedico,
                        'codEnfermedad' => $codEnfermedad,
                        'idEstado' => 4, #ES INJUSTIFICADO/ SE CIERRA EL CASO
                        'descripcion' => 'No tiene suficientes días para la enfermedad seleccionada.',
                    ];

                if ($this->certificadoModel->insert($data)) {
                    // Obtiene el ID del último registro insertado
                    $nuevoID = $this->certificadoModel->insertID();
                    $this->guardarImagen($ruta, $nuevoID, $archivoCertificado);
                    return redirect()->to('menu-empleado')->with('error', 'Certificado INJUSTIFICADO por dias insuficientes. Caso FINALIZADO.');
                }
            } else {
                $data =
                    [
                        'fechaEmision' => $fechaEmision,
                        'diasOtorgados' => $diasOtorgadosFecha,
                        'lugarReposo' => $direccionReposo,
                        'archivo' => 0,
                        'numeroTramite' => $numeroTramiteDeCasoActual,
                        'matricula' => $matriculaMedico,
                        'codEnfermedad' => $codEnfermedad,
                        'idEstado' => 4, #ES INJUSTIFICADO
                        'descripcion' => 'No tiene suficientes días para la enfermedad seleccionada.',
                    ];
                if ($this->certificadoModel->insert($data)) {
                    // Obtiene el ID del último registro insertado
                    $nuevoID = $this->certificadoModel->insertID();
                    $this->guardarImagen($ruta, $nuevoID, $archivoCertificado);
                    return redirect()->to('visualizarCasoE')->with('error', 'Certificado INJUSTIFICADO por dias insuficientes.');
                }
            }
        }
    }

    private function calcularFecha($fechaEmision, $diasReposo)
    {
        $fecha = Carbon::parse($fechaEmision);
        $diasReposo = (int)$diasReposo;
        return $diasReposo === 1 ? $fecha : $fecha->addDays($diasReposo)->format('Y-m-d');
    }


    public function buscarMedico($matriculaMedico)
    {
        $medico = $this->medicoSisaModel->find($matriculaMedico);
        if ($medico && $medico['habilitado']) {
            return $this->response->setJSON([
                'nombreCompleto' => $medico['apellido'] . ' ' . $medico['nombre']
            ]);
        } else {
            return $this->response->setJSON([
                'error' => 'El médico no existe o no está habilitado.'
            ]);
        }
    }

    public function verificardias($codEnfermedad, $diasOtorgados)
    {
        $session = session();
        $legajo = $session->get('legajo');
        $enfermedad = $this->enfermedadModel->find($codEnfermedad);
        $empleadoCategoria = $this->empleadoCategoriaModel->obtenerEmpleadoCategoria($legajo, $enfermedad['idCategoria']);

        if ($empleadoCategoria['diasDisponibles'] < $diasOtorgados) {
            return $this->response->setJSON([
                'error' => 'No tiene suficientes días para la enfermedad seleccionada.'
            ]);
        } else {
            return $this->response->setJSON([
                'confirmar' => 'Días válidos para la enfermedad seleccionada.'
            ]);
        }
    }

    public function verImagen($legajo, $id)
    {
        $ruta = WRITEPATH . "certificados/" . $legajo . "/" . $id;
        if (file_exists($ruta . ".jpg")) {
            $ruta = $ruta . ".jpg";
        } else {
            $ruta =  $ruta . ".png";
        }
        $mimeType = mime_content_type($ruta);
        header("Content-type: $mimeType");
        readfile($ruta);
        exit;
    }

    public function descargarCertificado($legajo, $id)
    {
        $ruta = WRITEPATH . "certificados/" . $legajo . "/" . $id;

        if (file_exists($ruta . ".jpg")) {
            $ruta = $ruta . ".jpg";
        } elseif (file_exists($ruta . ".png")) {
            $ruta = $ruta . ".png";
        }

        $nombre = 'certificado_' . time() . "." . pathinfo($ruta, PATHINFO_EXTENSION);

        $data = file_get_contents($ruta);

        return $this->response->download($ruta, null)->setFileName($nombre);
    }

    public function guardarImagen($ruta, $nuevoID, $archivoCertificado)
    {
        $nuevoNombre = $nuevoID . '.' . $archivoCertificado->getExtension();
        $archivoCertificado->move($ruta, $nuevoNombre);
    }



    public function asignarEstadoCertificado()
    {

        if ($this->request->getPost('nroTramite') != "") {
            $estadoCertificado = $this->request->getPost('estadoCertificado');
            $razon = $this->request->getPost('razon');
            $idCertificado = $this->request->getPost('idCertificado');
            $nroTramite = $this->request->getPost('nroTramite');
            $desde = $this->request->getPost('desdeForm');
            $hasta = $this->request->getPost('hastaForm');
            $legajo = $this->request->getPost('legajoForm');
            $fechaActual = date('Y-m-d');

            $estadoPendienteCertificado = 6;
            $estadoActivoCaso = 2;
            $categoriaCaso = $this->casoModel->obtenerTipoCategoriaVigente($nroTramite);




            if ($estadoCertificado == 4) {
                $this->invalidarCertificado($nroTramite, $idCertificado, $estadoCertificado, $razon);
                $this->casoModel->update($nroTramite, ['fechaFin' => $fechaActual]);
            } else {
                if ($this->certificadoModel->update_estado_certificado($idCertificado, $estadoCertificado, $razon)) {
                    if ($categoriaCaso == 1) {
                        $this->casoModel->actualizarEstadocaso($nroTramite, 3);
                        $this->descontarDias($desde, $hasta, $legajo, $idCertificado);
                    } else {
                        $this->casoModel->actualizarEstadocaso($nroTramite, 2);
                        $this->descontarDias($desde, $hasta, $legajo, $idCertificado);
                    }
                }
            }
        }
        echo $hasta;
        return redirect()->to('guardarNumeroTramite/' . $nroTramite);
    }

    public function invalidarCertificado($nroTramite, $idCertificado, $estadoCertificado, $razon)
    {

        $estadoFinalizadoCaso = 3;
        $estadoPendienteCertificado = 6;
        $busqueda = $this->certificadoModel->getCertificadosPorNumeroTramite($nroTramite);

        $this->casoModel->actualizarEstadocaso($nroTramite, $estadoFinalizadoCaso);


        // Asigno estado injustificado
        $this->certificadoModel->update_estado_certificado($idCertificado, $estadoCertificado, $razon);
    }

    public function descontarDias($desde, $hasta, $legajo, $idCertificado)
    {

        $fechaInicio = DateTime::createFromFormat('d/m/Y', $desde);
        $fechaFin = DateTime::createFromFormat('d/m/Y', $hasta);

        $fechaInicioString = $fechaInicio->format('Y-m-d');
        $fechaFinString = $fechaFin->format('Y-m-d');




        $diferencia = $fechaInicio->diff($fechaFin);
        $cantidadDiasDescuento = $diferencia->days;

        // OBTENGO LA ENFERMEDAD
        $unCertificado = $this->certificadoModel->obtenerUnCertificado($idCertificado);

        // OBTENGO LA GRAVEDAD DE LA ENFERMEDAD
        $gravedadEnfermedad = $this->enfermedadModel->obtenerUnaEnfermedad($unCertificado['codEnfermedad']);


        $this->casoModel->actualizarCaso($unCertificado['numeroTramite'], $fechaFinString, $gravedadEnfermedad['idCategoria'], $unCertificado['nombre'], $unCertificado['lugarReposo']);

        $cantidadDiasActual = $this->empleadoCategoriaModel->obtenerEmpleadoCategoria($legajo, $gravedadEnfermedad['idCategoria']);


        $cantidadDiasActualizado = $cantidadDiasActual['diasDisponibles'] - $cantidadDiasDescuento;
        $this->empleadoCategoriaModel->actualizarDiasDisponibles($legajo, $cantidadDiasActualizado, $gravedadEnfermedad['idCategoria']);
    }
}
