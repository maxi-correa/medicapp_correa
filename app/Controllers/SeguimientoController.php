<?php

namespace App\Controllers;

use App\Models\SeguimientoModel;
use App\Controllers\BaseController;
use App\Models\CasoModel;
use App\Models\TurnoModel;
use App\Models\CertificadoModel;
use App\Models\EmpleadoCategoriaModel;
use App\Models\EnfermedadModel;
use Carbon\Carbon;
use CodeIgniter\HTTP\ResponseInterface;

class SeguimientoController extends BaseController
{
    private $casoModel;
    private $seguimientoModel;
    private $turnoModel;
    private $certificadoModel;
    private $enfermedadModel;
    private $categoriaEmpleadoModel;
    private $columna = 'tipoSeguimiento';

    public function __construct()
    {
        $this->casoModel = new CasoModel();
        $this->seguimientoModel = new SeguimientoModel();
        $this->turnoModel = new TurnoModel();
        $this->categoriaEmpleadoModel = new EmpleadoCategoriaModel();
        $this->enfermedadModel = new EnfermedadModel();
        $this->certificadoModel = new CertificadoModel();
        Carbon::setLocale('es');
    }

    public function index()
    {
        $valoresEnum = $this->seguimientoModel->obtenerValoresEnum($this->columna);
        $idTurno = session()->get('idTurno');
        $turno = $this->turnoModel->find($idTurno);
        $seguimientoALTA = $this->seguimientoModel->where('idTurno', $idTurno)->where('tipoSeguimiento', $valoresEnum[0])->first(); //ALTA
        $seguimientoIRREGULAR = $this->seguimientoModel->where('idTurno', $idTurno)->where('tipoSeguimiento', $valoresEnum[1])->first(); //IRREGULAR
        $numeroTramite = $turno['numeroTramite'];

        $ahora = $this->fechaActual();
        $fechaTurno = Carbon::parse($turno['fecha']);
        $horaTurno = Carbon::parse($turno['hora']);
    
        $fechaHoraTurno = $fechaTurno->setTime($horaTurno->hour, $horaTurno->minute, $horaTurno->second)->subHours(6);
        //$fechaHoraTurno = $fechaTurno->setTime($horaTurno->hour, $horaTurno->minute, $horaTurno->second);
        $fechaFinTurno = $fechaHoraTurno->copy()->addHours(12   );

            if ($ahora->lessThan($fechaHoraTurno)) {
                log_message('debug', 'Redirigiendo porque la hora actual es menor al inicio del turno');
                return redirect()->to('visualizarCasoM');
            } elseif ($ahora->greaterThan($fechaFinTurno)) {
                log_message('debug', 'Redirigiendo porque la hora actual es mayor al fin del turno');
                return redirect()->to('visualizarCasoM');
            }

        if (!empty($seguimientoALTA)) {
            return redirect()->to('visualizarCasoM');
        }
        if (!empty($seguimientoIRREGULAR)) {
            return redirect()->to('visualizarCasoM');
        }

        $caso = $this->casoModel->find($numeroTramite);
        $diasDisponibles = $this->categoriaEmpleadoModel->obtenerCategoriaDeCaso($caso['legajo'], $caso['tipoCategoriaVigente']);
        $fechas =
            [
                'fechaInicio' => $caso['fechaInicio'],
                'fechaFin' => $caso['fechaFin'],
                'fechaActual' => $ahora->format('Y-m-d'),
                'diasDisponibles' => $diasDisponibles,
            ];
        $certificado = $this->certificadoModel->where('numeroTramite', $numeroTramite)
            ->where('idEstado', 5) // JUSTIFICADO
            ->where('fechaEmision <=', $turno['fecha'])
            ->orderBy('fechaEmision', 'DESC')
            ->first();
        if ($certificado) {
            $enfermedad = $this->enfermedadModel->find($certificado['codEnfermedad']);
            return view('turnos/seguimiento', ['valoresEnum' => $valoresEnum, 'fechas' => $fechas, 'certificado' => $certificado, 'enfermedad' => $enfermedad]);
        }
    }

    public function validar()
    {
        $valoresEnum = $this->seguimientoModel->obtenerValoresEnum($this->columna);
        $tipoSeguimiento = $this->request->getPost('tipoSeguimiento');
        $fechaSeguimiento = Carbon::parse($this->request->getPost('fechaSeguimiento'));
        $observacion = $this->request->getPost('observacion');
        $idTurno = session()->get('idTurno');
        $turno = $this->turnoModel->find($idTurno);
        $idSeguimiento = $this->seguimientoModel->generarIdSeguimiento($idTurno);
        $caso = $this->casoModel->find($turno['numeroTramite']);
        $fechaFinCaso = $caso['fechaFin'];
        $data =
            [
                'idTurno' => $idTurno,
                'idSeguimiento' => $idSeguimiento,
                'tipoSeguimiento' => $tipoSeguimiento,
                'fechaFinOriginalCaso' => $fechaFinCaso,
                'observacion' => $observacion,
            ];

        $certificado = $this->certificadoModel->where('numeroTramite', $caso['numeroTramite'])
            ->where('idEstado', 5) // JUSTIFICADO
            ->where('fechaEmision <=', $turno['fecha'])
            ->orderBy('fechaEmision', 'DESC')
            ->first();

        $fechaEmision = Carbon::parse($certificado['fechaEmision']);
        $fechaOtorgada = Carbon::parse($certificado['diasOtorgados']);
        $diferenciaDias = abs($fechaEmision->diffInDays($fechaOtorgada, false));
        $diferenciaDiasConIncluidos = $diferenciaDias + 1;
        $categoriaEmpleado = $this->categoriaEmpleadoModel->obtenerCategoriaDeCaso($caso['legajo'], $caso['tipoCategoriaVigente']);
        $diasDisponibles = $categoriaEmpleado['diasDisponibles'];
        $id = $categoriaEmpleado['id'];

        if ($tipoSeguimiento === $valoresEnum[0]) //ALTA
        {
            $this->turnoModel->update($turno['idTurno'], ['idEstado' => 8]);
            $this->casoModel->update($turno['numeroTramite'],['idEstado' => 3]);
            $this->casoModel->update($turno['numeroTramite'], ['fechaFin' => $fechaSeguimiento]);
            if ($fechaFinCaso !== $fechaSeguimiento) {
                $fechaFin= Carbon::parse($fechaFinCaso);
                $fechaAlta = Carbon::parse($fechaSeguimiento);
                $diferenciaDias = abs($fechaFin->diffInDays($fechaAlta, false));
                $diferenciaDiasConIncluidos = $diferenciaDias + 1;
                $diasDisponibles += $diferenciaDiasConIncluidos;
                $categoriaEmpleado['diasDisponibles'] = $diasDisponibles;
                $this->categoriaEmpleadoModel->update($id, $categoriaEmpleado);
            }
            $this->seguimientoModel->insert($data);
            return redirect()->to('visualizarCasoM')->with('success', 'Seguimiento guardado correctamente.');
        }
        if ($tipoSeguimiento === $valoresEnum[1]) //IRREGULAR
        {
            $diasDisponibles += $diferenciaDiasConIncluidos;
            $categoriaEmpleado['diasDisponibles'] = $diasDisponibles;
            $this->categoriaEmpleadoModel->update($id, $categoriaEmpleado);
            $certificado['idEstado'] = 4;
            $certificado['descripcion'] = 'IRREGULAR';
            $this->certificadoModel->update($certificado['idCertificado'], $certificado);
            $caso['idEstado'] = 3;
            $this->turnoModel->update($turno['idTurno'], ['idEstado' => 8]);
            $this->casoModel->update($caso['numeroTramite'], $caso);
            $this->seguimientoModel->insert($data);
            return redirect()->to('visualizarCasoM')->with('success', 'Seguimiento guardado correctamente.');
        }
        if ($tipoSeguimiento === $valoresEnum[2]) //PROXIMO TURNO
        {
            $this->casoModel->update($turno['numeroTramite'], ['fechaSugeridaTurno' => $fechaSeguimiento]);
            $data['diasParaProximoTurno'] =  $fechaSeguimiento;
            $this->turnoModel->update($turno['idTurno'], ['idEstado'=> 9]);
            $this->seguimientoModel->insert($data); 
            return redirect()->to('visualizarCasoM')->with('success', 'Seguimiento guardado correctamente.');
        }
        if ($tipoSeguimiento === $valoresEnum[3]) // EXTENDER PLAZO 
        {
            $this->casoModel->update($turno['numeroTramite'], ['fechaFin' => $fechaSeguimiento]);
            $this->casoModel->update($caso['idEstado'], ['idEstado' => 3]);
            $this->turnoModel->update($turno['idTurno'], ['idEstado'=> 8]);
            $this->seguimientoModel->insert($data);
            return redirect()->to('visualizarCasoM')->with('success', 'Seguimiento guardado correctamente.');
        }

        return redirect()->back()->with('error', 'Hubo un error al guardar el seguimiento.');
    }

    public function obtenerDiaDeFecha($fecha)
    {
        $fecha = Carbon::parse($fecha);
        $diaEnTexto = $fecha->translatedFormat('l');
        $diaEnTexto = ucfirst(strtolower($diaEnTexto));
        return $diaEnTexto;
    } 

    public function fechaActual()
    {
        return Carbon::now();
    }
}
