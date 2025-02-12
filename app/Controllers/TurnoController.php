<?php

namespace App\Controllers;

use App\Models\EmpleadoModel;
use App\Models\HorarioModel;
use App\Models\MedicoAuditorModel;
use App\Models\TurnoModel;
use App\Controllers\BaseController;
use App\Models\CasoModel;
use App\Models\CertificadoModel;
use App\Models\SeguimientoModel;
use Carbon\Carbon;
use DateTime;
use CodeIgniter\HTTP\ResponseInterface;
use Faker\Provider\Base;

class TurnoController extends BaseController
{
    protected $empleadoModel;
    protected $casoModel;
    protected $horarioModel;
    protected $turnoModel;
    protected $medicoAuditorModel;
    protected $certificadoModel;
    protected $seguimientoModel;
    protected $caso;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->casoModel = new CasoModel();
        $this->horarioModel = new HorarioModel();
        $this->turnoModel = new TurnoModel();
        $this->medicoAuditorModel = new MedicoAuditorModel();
        $this->certificadoModel = new CertificadoModel();
        $this->seguimientoModel = new SeguimientoModel();
        Carbon::setLocale('es');
    }

    public function turnos()
    {
        $session = session();
        $matricula = $session->get('matricula');
        $turnos = $this->turnoModel->obtenerTurnosMedico($matricula);
        return view('medicoAuditor/menu', ['turnos' => $turnos]);
    }


    public function listar($semanaOffset = 0)
    {
        $fechaActual = new DateTime(); // Fecha actual
        // Ajusta la fecha de inicio a la semana actual más el offset
        $fechaInicio = $fechaActual->modify('monday this week')->modify("+$semanaOffset week")->format('Y-m-d');

        $matricula = session()->get('matricula');

        $userRole = session()->get('rol');
        $view = '';
        // Obtener los turnos para la semana actual
        $turnosPorDia = [];
        for ($i = 0; $i < 7; $i++) {
            $fecha = (new DateTime($fechaInicio))->modify("+$i day")->format('Y-m-d');
            // Asegúrate de que este método exista y funcione correctamente
            if ($userRole === 'Admin. Medicina Laboral') {
                $turnosPorDia[$fecha] = $this->turnoModel->obtenerTurnosPorDia($fecha);
            } else if ($userRole === 'Medico') {
                $turnosPorDia[$fecha] = $this->turnoModel->obtenerTurnosPorDiaMedic($fecha, $matricula);
            }
        }



        if ($userRole === 'Admin. Medicina Laboral') {
            $view = 'turnos/listar';
        } else if ($userRole === 'Medico') {
            $view = 'turnos/listarTurnosMed';
        } else {
            // Opcional: redirigir o mostrar un mensaje si el rol no es válido
            return redirect()->to('/')->with('error', 'No tiene permiso para acceder a esta página');
        }

        return view($view, [
            'turnosPorDia' => $turnosPorDia,
            'semanaOffset' => $semanaOffset // Envia el offset actual para la navegación
        ]);
        /*// Pasar los datos a la vista
        return view('turnos/listar', [
            'turnosPorDia' => $turnosPorDia,
            'semanaOffset' => $semanaOffset // Envia el offset actual para la navegación
        ]);*/
    }

    private function obtenerCasoActivo()
    {
        $session = session();
        $numeroTramite = $session->get('numeroTramite');
        return $this->casoModel->find($numeroTramite);
    }

    public function creoTurno()
    {
        $caso = $this->obtenerCasoActivo();
        if ($caso['tipoCategoriaVigente'] === '1' || empty($caso['tipoCategoriaVigente']) || $caso['idEstado'] ==='3') {
            return redirect()->to('visualizarCasoA');
        } else {
            if (!empty($this->turnoModel->obtenerTurnosPendientes($caso['numeroTramite'])))
            {
                return redirect()->to('visualizarCasoA');
            } else {
            $medicos = $this->medicoAuditorModel->obtenerMedicosHabilitados();
            $fecha = $this->obtenerFechaTurno();
            $dia = $this->obtenerDiaDeFecha($fecha);
            $inicio = Carbon::parse($caso['fechaInicio']);
            $fin = Carbon::parse($caso['fechaFin']);
            $diasFaltantes = 1 + ($inicio->diffInDays($fin));
            $empleado = $this->empleadoModel->find($caso['legajo']);
            return view('turnos/formulario', ['caso' => $caso, 'diasFaltantes' => $diasFaltantes, 'medicos' => $medicos, 'fecha' => $fecha, 'dia' => $dia, 'empleado' => $empleado]);
            }
        }
    }

    public function validar()
    {
        $matricula = $this->request->getPost('matricula');
        $horario = $this->request->getPost('horario');
        $fecha = $this->obtenerFechaTurno();
        $caso = $this->obtenerCasoActivo();
        $data =
            [
                'fecha' => $fecha,
                'hora' => $horario,
                'lugar' => $caso['lugarReposo'],
                'motivo' => $caso['motivo'],
                'numeroTramite' => $caso['numeroTramite'],
                'matricula' => $matricula,
                'idEstado' => 10, ///PENDIENTEE
            ];
        if ($this->turnoModel->insert($data)) {
            return redirect()->to('visualizarCasoA')->with('success', 'Turno guardado correctamente.');
        } else {
            return redirect()->back()->with('error', 'Hubo un error al guardar el turno.');
        }
    }

    public function mostrarTurnos($matricula)
    {
        $caso = $this->obtenerCasoActivo();
        $fecha = $this->obtenerFechaTurno();
        $dia = $this->obtenerDiaDeFecha($fecha);
        $tope = 10;

        $datosTurno = [
            'fecha' => $fecha,
            'dia' => $dia,
            'caso' => $caso,
        ];

        $horariosDiaEspeficico = $this->horarioModel->obtenerHorarioDiaEspecifico($matricula, $dia);
        if (empty($horariosDiaEspeficico)) {
            return $this->response->setJSON([
                'trabaja' => 'No trabaja los ' . $dia,
            ]);
        } else {
            $horarios = $this->obtenerTurnosLibres($matricula, $fecha, $horariosDiaEspeficico, 10);
            if (empty($horarios)) {
                return $this->response->setJSON([
                    'error' => 'No hay horarios disponibles'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => true,
                    'horarios' => $horarios,
                    'datos' => $datosTurno,
                ]);
            }
        }
    }

    private function obtenerFechaTurno()
    {
        $fechaSeleccionada = $this->request->getPost('fecha'); // Obtener fecha del formulario
        if ($fechaSeleccionada) {
            return Carbon::parse($fechaSeleccionada)->format('Y-m-d');
        }

        $caso = $this->obtenerCasoActivo();
        $fechaSugeridaTurno = isset($caso['fechaSugeridaTurno']) ? Carbon::parse($caso['fechaSugeridaTurno']) : null;
        $fechaActual = $this->fechaActual();

        if ($fechaSugeridaTurno && $fechaSugeridaTurno->greaterThanOrEqualTo($fechaActual)) 
        
        /*{
            $fechaReferencia = $this->esDiaHabil($fechaSugeridaTurno)
                ? $fechaSugeridaTurno
                : $this->obtenerProximaFechaHabil($fechaSugeridaTurno);
        } else {
            $fechaReferencia = $this->obtenerProximaFechaHabil($fechaActual);
        }
        return $fechaReferencia->format('Y-m-d');*/
        {
            return $fechaSugeridaTurno->format('Y-m-d');
        }
        return $fechaActual->format('Y-m-d');
    }

    private function esDiaHabil($fecha)
    {
        return $fecha->isWeekday();
    }

    private function obtenerProximaFechaHabil($fechaReferencia)
    {
        if ($fechaReferencia->isSaturday()) {
            $fechaReferencia->addDays(2);
        } elseif ($fechaReferencia->isSunday()) {
            $fechaReferencia->addDay();
        } else {
            while (!$this->esDiaHabil($fechaReferencia)) {
                $fechaReferencia->addDay();
            }
        }
        return $fechaReferencia;
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

    private function obtenerTurnosLibres($matricula, $fechaTurno, $horariosDiaEspeficico, $tope)
    {
        $turnos = [];
        foreach ($horariosDiaEspeficico as $horario) {
            $horaInicio = Carbon::parse($horario['horaInicio']);
            $horaFin =  Carbon::parse($horario['horaFin']);
            $duracion = (int) $horario['duracion'];
            while ($horaInicio < $horaFin) {
                if (!$this->turnoModel->verSiHayTurnoEnEseHorario($matricula, $fechaTurno, $horaInicio)) {
                    $horaFinTurno = $horaInicio->copy()->addMinutes($duracion);
                    $turnos[] = [
                        'id' => $horaInicio->toTimeString(),
                        'horaInicio' => $horaInicio->format('G:i') . ' hs',
                        'horaFin' => $horaFinTurno->format('G:i') . ' hs',
                    ];
                }
                $horaInicio->addMinutes($duracion);
            }
        }
        return $turnos;
    }

    private function buscarTurnoAlternativo($matricula, $fechaTurno, $dia)
    {
        $fechaActual = Carbon::now()->format('Y-m-d');
        if ($fechaActual == $fechaTurno) {
        }
    }

    public function verificarTurnosCaducados()
    {
        $fechaActual = Carbon::now()->endOfDay();

        $turnosCaducados = $this->turnoModel->where('fecha <=', $fechaActual)
            ->where('idEstado', '10') //TURNO PENDIENTE
            ->findAll();

        foreach ($turnosCaducados as $turno) {
            foreach ($turnosCaducados as $turno) {
                $this->turnoModel->update($turno['idTurno'], ['idEstado' => '8']);
            }
        }

        echo "Verificación completada: Turnos cerrados si estaban caducados.";
    }

    public function guardarIdTurno($idTurno)
    {

        $numeroTramite = $this->turnoModel->obtenerNumeroTramitePorIdTurno($idTurno);
        session()->set('numeroTramite', $numeroTramite);
        session()->set('idTurno', $idTurno);
        $turno = $this->turnoModel->find($idTurno);
        session()->set('fechaTurno',$turno['fecha']);
        session()->set('horaTurno',$turno['hora']);


        $rolInicioSesion = session()->get('rol');

        if ($rolInicioSesion == "Medico") {

            return redirect()->to('visualizarCasoM');
        }
        
    }


function actualizarFechaInicio($caso) {
    // Validar si existe fechaSugeridaTurno y es diferente a fechaInicio
    if (isset($caso['fechaSugeridaTurno']) && $caso['fechaSugeridaTurno'] !== $caso['fechaInicio']) {
        $caso['fechaInicio'] = $caso['fechaSugeridaTurno'];
    }
    return $caso;
}

function finalizarCasoSiCorresponde($caso) {
    // Obtener la fecha actual en formato 'Y-m-d'
    $fechaActual = date('Y-m-d');
    
    // Validar si fechaFin es menor a la fecha actual
    if (isset($caso['fechaFin']) && $caso['fechaFin'] < $fechaActual) {
        $caso['estado'] = 'finalizado'; // Cambiar estado a finalizado
    }
    return $caso;
}


function cancelarTurnosMedico($matricula) {
    // Llamamos a la función cancelarTurnos del modelo
    $resultado = $this->turnoModel->cancelarTurnos($matricula);

    if ($resultado) {
        // Si se cancelaron los turnos, mostrar mensaje de éxito
        echo "Los turnos han sido cancelados para el médico con matrícula: " . $matricula;
    } else {
        // Si no, mostrar mensaje de error
        echo "No se encontraron turnos para cancelar para el médico con matrícula: " . $matricula;
    }
}

}