<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MedicoAuditorModel;
use App\Models\HorarioModel;
use App\Models\TurnoModel;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Mimes;

class MedicoAuditorController extends BaseController
{
    protected $medicoAuditorModel;
    protected $horarioModel;
    protected $turnoModel;

    public function __construct()
    {
        $this->medicoAuditorModel = new MedicoAuditorModel();
        $this->horarioModel = new HorarioModel();
        $this->turnoModel = new TurnoModel();
    }



    public function listar()
    {
        $medicos = $this->medicoAuditorModel->obtenerMedicos();
        $medicosHab = $this->medicoAuditorModel->obtenerMedicosHabilitados();
        if (!empty($medicos)) {
            return view('medicoAuditor/listar', [
                'medicos' => $medicos,
                'medicosHab' => $medicosHab
            ]);
        }
    }

    public function crear()
    {
        return view('medicoAuditor/crear');
    }

    public function guardar()
    {
        log_message('debug', 'Entrando en el método guardar del controlador MedicoController');
        $matricula = 'M' . $this->request->getPost('matricula');
        $data = [
            'matricula'       => $matricula,
            'nombre'          => $this->request->getPost('nombre'),
            'apellido'        => $this->request->getPost('apellido'),
            'dni'             => $this->request->getPost('dni'),
            'fechaNacimiento' => $this->request->getPost('fechaNacimiento'),
            'gmail'           => $this->request->getPost('gmail'),
            'telefono'        => $this->request->getPost('telefono'),
            'especialidad'    => $this->request->getPost('especialidad'),
            /*'contrasenia'     => $this->medicoAuditorModel->hashPasswordString($this->request->getPost('contrasenia')),*/
            'contrasenia'     => $this->request->getPost('contrasenia'),
        ];

        if ($this->medicoAuditorModel->crearMedico($data)) {
            return redirect()->to('/medicos/informacion/' . $matricula)->with('message', 'Médico creado con éxito');
        } else {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear el médico');
        }
    }

    public function obtenerIdHorario($matricula)
    {
        $ultimoId = $this->horarioModel->selectMax('idHorario')
            ->where('matricula', $matricula)
            ->first();

        return isset($ultimoId['idHorario']) ? $ultimoId['idHorario'] + 1 : 1;
    }

    public function guardarHorario($matricula, $dia)
    {
        $datos = $this->request->getPost();
        $idHorario = $this->obtenerIdHorario($matricula);
        $horario = [
            'idHorario' => $idHorario,
            'matricula'   => $matricula,
            'diaSemana'   => $dia,
            'horaInicio'  => $datos['horaInicio'],
            'horaFin'     => $datos['horaFin'],
            'duracion'    => $datos['duracion'],
        ];
        $this->horarioModel->insert($horario);
        return redirect()->to('medicos/informacion/' . $matricula . '/' . urlencode($dia));
    }

    public function obtenerMedicos()
    {

        // Verificar si hay médicos
        if (!empty($medicos)) {
            // Devolver los datos en formato JSON
            return $this->response->setJSON($medicos);
        } else {
            // Si no se encuentran médicos, devolver un array vacío
            return $this->response->setJSON([]);
        }
    }

    public function mostrarInformacion($matricula)
    {
        $horarios = $this->horarioModel->obtenerHorariosSemanalesMedico($matricula);
        $nombre = $this->medicoAuditorModel->obtenerMedico($matricula);

        return view('medicoAuditor/informacion', [
            'matricula' => $matricula,
            'horarios' => $horarios,
            'nombre' => $nombre
        ]);
    }

    public function mostrarInformacionDia($matricula, $dia)
    {
        $diaNormalizado = eliminarTildes($dia);
        $horarios = $this->horarioModel->obtenerHorarioDiaEspecifico($matricula, $diaNormalizado);
        $nombre = $this->medicoAuditorModel->obtenerMedico($matricula);

        if (!empty($horarios)) {
            // Si hay horarios, pasar los datos a la vista
            return view('medicoAuditor/informacionDia', [
                'horarios' => $horarios,
                'dia' => $dia,
                'matricula' => $matricula,
                'nombre' => $nombre
            ]);
        }
    }

    public function mostrarFormularioHorario($matricula, $dia)
    {
        return view('medicoAuditor/formularioHorario', ['matricula' => $matricula, 'dia' => $dia]);
    }

    public function obtenerHorariosMedico()
    {
        // Accede a la sesión
        $session = session();
        $matricula = $session->get('matricula'); // Obtén la matrícula del médico logueado

        // Verifica que la matrícula esté en la sesión
        if (!$matricula) {
            return redirect()->to('/login'); // Redirige al login si no está logueado
        }

        // Cargar el modelo
        $horariosModel = new MedicoAuditorModel();

        // Obtener los horarios del médico
        $horarios = $horariosModel->obtenerHorariosPorMatricula($matricula);

        // Pasar los horarios a la vista
        return view('medicoAuditor/horarios', [
            'horarios' => $horarios
        ]);
    }

    public function verMedico($matricula)
    {

        $medico = $this->medicoAuditorModel->obtenerMedico($matricula);
        $horariosMed = $this->medicoAuditorModel->obtenerHorarioMedico($matricula);
        $turnosMed = $this->turnoModel->obtenerTurnosMedico($matricula);
        return view('medicoAuditor/verMedico', [
            'medico' => $medico,
            'horariosMed' => $horariosMed,
            'turnosMed' => $turnosMed
        ]);
    }

    public function deshabilitarTurnos($matricula, $cantidadDias)
    {
        // Verificar si los datos están correctamente recibidos a través de la URL
        if ($cantidadDias == null || empty($matricula)) {
            // Si los datos son nulos o vacíos, responder con un error
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        // Validar los datos recibidos
        if ($cantidadDias <= 0 || empty($matricula)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos']);
        }

        $this->medicoAuditorModel->deshabilitarMedico($matricula);

        // Obtener la fecha actual
        $fechaActual = date('Y-m-d');

        // Calcular la fecha límite (la fecha actual + cantidad de días)
        $fechaLimite = date('Y-m-d', strtotime("+$cantidadDias days", strtotime($fechaActual)));


        // Obtener los turnos del médico dentro del rango de fechas
        $turnos = $this->turnoModel->where('matricula', $matricula)
            ->where('fecha >=', $fechaActual)
            ->where('fecha <=', $fechaLimite)
            ->findAll();

        // Verificar si no se encontraron turnos
        if (empty($turnos)) {
            // Establecer el mensaje de éxito en la sesión
            session()->setFlashdata('success', 'El médico no posee turnos entre esta fecha y los días inhabilitados por ende, no se modifico ningún turno');
            return redirect()->to(base_url('medicos/deshabilitar/' . $matricula));  // Redirigir al usuario
        }

        // Recorrer los turnos y actualizar la fecha de cada uno
        foreach ($turnos as $turno) {
            $nuevaFecha = strtotime("+$cantidadDias days", strtotime($turno['fecha']));
            $nuevaFecha = date('Y-m-d', $nuevaFecha);  // Convertir la nueva fecha en formato adecuado

            // Actualizar el turno
            $this->turnoModel->update($turno['idTurno'], [
                'fecha' => $nuevaFecha
            ]);
        }

        // Establecer el mensaje de éxito en la sesión
        session()->setFlashdata('success', 'El medico han sido deshabilitados correctamente.');

        // Redirigir al usuario a la página correspondiente
        return redirect()->to(base_url('medicos/deshabilitar/' . $matricula));  // Ajusta la URL según sea necesario
    }

    public function habilitarMedico($matricula)
    {
        // Realiza la operación en la base de datos
        $result = $this->medicoAuditorModel->habilitarMedico($matricula);

        // Verificar si la operación fue exitosa
        if ($result) {
            // Devolver respuesta JSON de éxito
            return $this->response->setJSON(['success' => true]);
        } else {
            // Devolver respuesta JSON de error
            return $this->response->setJSON(['success' => false, 'message' => 'Hubo un error al habilitar al médico.']);
        }
    }

    public function deshabilitarMedico($matricula)
    {
        // Llamar al modelo para deshabilitar al médico
        $resultado = $this->medicoAuditorModel->deshabilitarMedico($matricula);

        // Llamar al método cancelarTurnosMedico para cancelar los turnos del médico
        $this->turnoModel->cancelarTurnos($matricula);

        // Verificar si la operación de deshabilitar fue exitosa
        if ($resultado) {
            // Si ambos procesos fueron exitosos, devolver respuesta JSON con éxito
            return $this->response->setJSON(['success' => true, 'message' => 'El médico ha sido deshabilitado y sus turnos han sido cancelados correctamente.']);
        } else {
            // Si hubo un error en alguno de los procesos, devolver respuesta JSON con error
            return $this->response->setJSON(['success' => false, 'message' => 'Hubo un error al deshabilitar al médico o cancelar sus turnos.']);
        }
    }

    public function deshabilitarMedicoTemporalmente()
    {
        $json = $this->request->getJSON(); // Recibe los datos del AJAX
        $matricula = $json->matricula;
        $dias = $json->dias;
        $json = $this->request->getJSON();
        $horariosNuevo = [];
        log_message('debug', 'Datos recibidos: ' . print_r($json, true)); // Agregar log

        if (!$matricula || !$dias || $dias <= 0) {
            return $this->response->setJSON(["success" => false, "message" => "Datos inválidos"]);
        }

        $db = \Config\Database::connect();

        // Deshabilitar al médico
        $db->table('medicos_auditores')
            ->where('matricula', $matricula)
            ->update(['habilitado' => '0']);

        // Obtener la fecha actual en formato YYYY-MM-DD
        $fechaActual = date('Y-m-d');

        // Calcular la fecha límite (fecha actual + cantidad de días)
        $fechaLimite = date('Y-m-d', strtotime("+$dias days", strtotime($fechaActual)));

        // Obtener los turnos del médico dentro del rango de fechas
        $turnos = $this->turnoModel->where('matricula', $matricula)
            ->where('fecha >=', $fechaActual)
            ->where('fecha <=', $fechaLimite)
            ->findAll();

        // Obtener los días de trabajo del médico
        $horarios = $this->horarioModel->where('matricula', $matricula)->findAll();

        // Crear un mapa de horarios por día de la semana
        $horariosPorDia = [];
        foreach ($horarios as $horario) {
            $horariosPorDia[$horario['diaSemana']][] = [
                'idHorario' => $horario['idHorario'],
                'horaInicio' => $horario['horaInicio'],
                'horaFin' => $horario['horaFin'],
                'duracion' => $horario['duracion']
            ];
        }

        // Mapeo de días de la semana (1 = Lunes, ..., 7 = Domingo)
        $diasDeLaSemana = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miercoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sabado',
            7 => 'Domingo'
        ];

        for ($i = 1; $i <= 14; $i++) {
            $fechaNueva = date('Y-m-d', strtotime("+$i day", strtotime($fechaLimite))); // Sumamos días desde la fecha límite
            $diaSemana = date('N', strtotime($fechaNueva)); // Obtener el número del día de la semana (1 = Lunes, ..., 7 = Domingo)
            $diaNombre = $diasDeLaSemana[$diaSemana]; // Obtener el nombre del día en español

            if (isset($horariosPorDia[$diaNombre])) {  // Verifica si la clave existe
                foreach ($horariosPorDia[$diaNombre] as $horario) {
                    $horariosNuevo[] = [
                        'idHorario' => $horario['idHorario'],
                        'fecha' => $fechaNueva, // Asignamos la fecha correspondiente
                        'horaInicio' => $horario['horaInicio'],
                        'horaFin' => $horario['horaFin']
                    ];
                }
            }
        }

        //HORARIOS NUEVOS Y TURNOS
        foreach ($horariosNuevo as $horarioNuevo) {
            // Verificar si hay turnos en esa fecha
            $turnosExistentes = $this->turnoModel
                ->where('fecha', $horarioNuevo['fecha'])
                ->where('matricula', $matricula)
                ->findAll();
            
            // Si no existen turnos, o si existen pero se van a modificar, procesamos
            if (empty($turnosExistentes)) {
                // Buscar un turno disponible (esto depende de cómo gestiones los turnos)
                // Si la lógica que usas es que los turnos deben ser reasignados, tomamos el primero disponible
                $turno = array_shift($turnos);  // Asumimos que $turnos es un array con turnos previos disponibles
                
                if ($turno) { // Verificar que el turno existe
                    // Actualizar el turno con la nueva fecha
                    $this->turnoModel->update($turno['idTurno'], ['fecha' => $horarioNuevo['fecha']]);
                    
                    log_message('debug', 'Turno actualizado: ' . print_r($turno, true));
                }
            }
        }

        log_message('debug', 'Horarios nuevos generados: ' . print_r($horariosNuevo, true));
        log_message('debug', 'Horarios nuevos generados después del bucle: ' . print_r($horariosPorDia, true));
        log_message('debug', 'Turnos ' . print_r($turnos, true));

        return $this->response->setJSON(["success" => true, "message" => "Médico deshabilitado por $dias días y turnos reubicados"]);
    }
}
