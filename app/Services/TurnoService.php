<?php

namespace App\Services;

use App\Models\TurnoModel;
use App\Models\MedicoAuditorModel;
use App\Models\CasoModel;
use App\Models\HorarioModel;
use Carbon\Carbon;

class TurnoService
{
    protected TurnoModel $turnoModel;
    protected MedicoAuditorModel $medicoAuditorModel;
    protected CasoModel $casoModel;
    protected HorarioModel $horarioModel;
    protected $db;

    public function __construct()
    {
        $this->turnoModel = new TurnoModel();
        $this->medicoAuditorModel = new MedicoAuditorModel();
        $this->casoModel = new CasoModel();
        $this->horarioModel = new HorarioModel();
        $this->db = \Config\Database::connect();
    }
    /*
    |--------------------------------------------------------------------------
    | Cancelar turnos caducados
    |--------------------------------------------------------------------------
    */
    public function cancelarTurnosCaducados(): void
    {
        $limite = Carbon::now()->subHours(6)->format('Y-m-d H:i:s');

        $sql = "
            UPDATE turnos t
            LEFT JOIN seguimientos s ON s.idTurno = t.idTurno
            SET t.idEstado = 8
            WHERE t.idEstado = 10
            AND TIMESTAMP(t.fecha, t.hora) < ?
            AND s.idTurno IS NULL
        ";

        $this->db->query($sql, [$limite]);
    }

    /*
    |--------------------------------------------------------------------------
    | Reprogramar turnos por ausencia de médico y deshabilitarlo temporalmente
    |--------------------------------------------------------------------------
    */
    public function reprogramarTurnosPorAusencia(string $matricula, string $fechaDesde, string $fechaHasta): array
    {
        $this->db->transStart();

        $reprogramados = 0;
        $cancelados = 0;

        // 1. Turnos afectados
        $turnos = $this->turnoModel
            ->where('matricula', $matricula)
            ->where('fecha >=', $fechaDesde)
            ->where('fecha <=', $fechaHasta)
            ->where('idEstado', 10) // Pendiente
            ->findAll();

        // 2. Médicos habilitados
        $medicos = $this->medicoAuditorModel
            ->where('habilitado', 1)
            ->where('matricula !=', $matricula)
            ->findAll();

        foreach ($turnos as $turno) {

            // Validar caso activo
            $caso = $this->casoModel
                ->where('numeroTramite', $turno['numeroTramite'])
                ->first();

            if (!$caso || $caso['idEstado'] != 2) { // Si el caso no existe o no está activo, cancelar sin intentar reprogramar
                $this->cancelarTurno($turno['idTurno']);
                $cancelados++;
                continue;
            }

            $reasignado = $this->intentarReasignacion($turno, $medicos);

            if ($reasignado) {
                $reprogramados++;
            } else {
                $this->cancelarTurno($turno['idTurno']);
                $cancelados++;
            }
        }

        // Deshabilitar al médico
        $this->medicoAuditorModel
            ->where('matricula', $matricula)
            ->set(['habilitado' => 0])
            ->update();

        $this->db->transComplete();

        return [
            'reprogramados' => $reprogramados,
            'cancelados' => $cancelados
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Intentar reasignar un turno
    |--------------------------------------------------------------------------
    */
    private function intentarReasignacion(array $turno, array $medicos): bool
    {
        $fechaInicioBusqueda = Carbon::parse($turno['fecha'])->addDay();
        $limiteBusqueda = $fechaInicioBusqueda->copy()->addDays(30);

        while ($fechaInicioBusqueda <= $limiteBusqueda) {

            $fechaActual = $fechaInicioBusqueda->format('Y-m-d');
            $diaSemana = $this->obtenerNombreDia($fechaActual);

            $medicosOrdenados = $this->ordenarMedicosPorCarga($medicos, $fechaActual, $diaSemana);

            foreach ($medicosOrdenados as $medico) {

                $horarios = $this->horarioModel
                    ->where('matricula', $medico['matricula'])
                    ->where('diaSemana', $diaSemana)
                    ->findAll();

                foreach ($horarios as $horario) {

                    $bloques = $this->generarBloques($horario);

                    foreach ($bloques as $hora) {

                        $ocupado = $this->turnoModel
                            ->where('matricula', $medico['matricula'])
                            ->where('fecha', $fechaActual)
                            ->where('hora', $hora)
                            ->first();

                        if (!$ocupado) {

                            // Marcar viejo como reprogramado
                            $this->turnoModel->update($turno['idTurno'], [
                                'idEstado' => 9, // Reprogramado
                            ]);

                            // Crear nuevo turno
                            $this->turnoModel->insert([
                                'fecha' => $fechaActual,
                                'hora' => $hora,
                                'lugar' => $turno['lugar'],
                                'motivo' => $turno['motivo'],
                                'numeroTramite' => $turno['numeroTramite'],
                                'matricula' => $medico['matricula'],
                                'idEstado' => 10 // Pendiente
                            ]);

                            return true;
                        }
                    }
                }
            }

            $fechaInicioBusqueda->addDay();
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Ordenar médicos por menor carga en el día
    |--------------------------------------------------------------------------
    */
    private function ordenarMedicosPorCarga(array $medicos, string $fecha, string $diaSemana): array
    {
        $lista = [];

        foreach ($medicos as $medico) {

            $trabaja = $this->horarioModel
                ->where('matricula', $medico['matricula'])
                ->where('diaSemana', $diaSemana)
                ->first();

            if ($trabaja) {

                $cantidad = $this->turnoModel
                    ->where('matricula', $medico['matricula'])
                    ->where('fecha', $fecha)
                    ->countAllResults();

                $lista[] = [
                    'matricula' => $medico['matricula'],
                    'turnos' => $cantidad
                ];
            }
        }

        shuffle($lista); // Mezclar para evitar siempre el mismo orden en caso de empate

        // Ordenar por cantidad de turnos (menor a mayor)
        usort($lista, function ($a, $b) {
            return $a['turnos'] <=> $b['turnos'];
        });

        return $lista;
    }

    /*
    |--------------------------------------------------------------------------
    | Generar bloques según duración
    |--------------------------------------------------------------------------
    */
    private function generarBloques(array $horario): array
    {
        $bloques = [];

        $inicio = strtotime($horario['horaInicio']);
        $fin = strtotime($horario['horaFin']);
        $duracion = $horario['duracion'] * 60;

        while ($inicio < $fin) {
            $bloques[] = date('H:i:s', $inicio);
            $inicio += $duracion;
        }

        return $bloques;
    }

    /*
    |--------------------------------------------------------------------------
    | Cancelar turno
    |--------------------------------------------------------------------------
    */
    private function cancelarTurno(int $idTurno): void
    {
        $this->turnoModel->update($idTurno, [
            'idEstado' => 7
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener nombre del día en español
    |--------------------------------------------------------------------------
    */
    private function obtenerNombreDia(string $fecha): string
    {
        $dias = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miercoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sabado',
            'Sunday' => 'Domingo'
        ];

        return $dias[Carbon::parse($fecha)->format('l')]; // 'l' devuelve el nombre del día en inglés
    }
}
