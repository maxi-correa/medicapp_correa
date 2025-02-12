<?php

namespace App\Models;

use CodeIgniter\Model;
use Carbon\Carbon;

class HorarioModel extends Model
{
    protected $table            = 'horarios';
    protected $primaryKey       = 'idHorario';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['matricula', 'diaSemana', 'horaInicio', 'horaFin', 'duracion'];

    public function generarIdHorario($matricula)
    {
        $ultimoHorario = $this->where('matricula', $matricula)
            ->orderBy('idHorario', 'desc')
            ->first();

        return $ultimoHorario ? $ultimoHorario['idHorario'] + 1 : 1;
    }
    public function obtenerHorariosMedico($matricula)
    {
        return $this->where('matricula', $matricula)
            ->findAll();
    }

    public function obtenerHorariosSemanalesMedico($matricula)
    {
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

    // Obtener los horarios del médico
        $horarios = $this->where('matricula', $matricula)
                    ->findAll();

    // Crear un array para asegurarnos de tener todos los días de la semana
        $horariosConDias = [];

        foreach ($diasSemana as $dia) {
        // Buscar el horario para cada día
            $horarioDia = null;
            foreach ($horarios as $horario) {
                if ($horario['diaSemana'] == $dia) {
                    $horarioDia = $horario;
                    break;
                }
        }

        // Si no existe horario para el día, agregar un valor vacío
            if ($horarioDia === null) {
                $horariosConDias[] = ['diaSemana' => $dia, 'horaInicio' => '', 'horaFin' => ''];
            } else {
                // Agregar el horario al array de días
                $horariosConDias[] = $horarioDia;
            }
    }

        return $horariosConDias;
    }


    public function obtenerHorarioDiaEspecifico($matricula, $dia)
    {
        return $this->where('matricula', $matricula)
                    ->where('diaSemana', $dia)
                    ->findAll();
    }
    public function obtenerDiasQueTrabajaMedico($matricula)
    {
        $resultados = $this->where('matricula', $matricula)
            ->distinct()
            ->select('diaSemana')
            ->findAll();
        $dias = [];
        foreach ($resultados as $registro) {
            $dias[] = $registro['diaSemana'];
        }
        return $dias;
    }

    public function obtenerProximoYAnteriorDia($matricula, $diaEspecifico)
    {
        $diasTrabajados = $this->obtenerDiasQueTrabajaMedico($matricula);
        $todosLosDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        usort($diasTrabajados, function($a, $b) use ($todosLosDias) {
            return array_search($a, $todosLosDias) - array_search($b, $todosLosDias);
        });
        
        $diaAnterior = null;
        $diaSiguiente = null;
        
        $indiceDiaEspecifico = array_search($diaEspecifico, $todosLosDias);
        
        for ($i = $indiceDiaEspecifico - 1; $i >= $indiceDiaEspecifico - 7; $i--) {
            $indiceCircular = ($i + 7) % 7;
            if (in_array($todosLosDias[$indiceCircular], $diasTrabajados)) {
                $diaAnterior = $todosLosDias[$indiceCircular];
                break;
            }
        }
    
        for ($i = $indiceDiaEspecifico + 1; $i < $indiceDiaEspecifico + 7; $i++) {
            $indiceCircular = $i % 7;
            if (in_array($todosLosDias[$indiceCircular], $diasTrabajados)) {
                $diaSiguiente = $todosLosDias[$indiceCircular];
                break;
            }
        }
    
        return [
            'diaAnterior' => $diaAnterior,
            'diaSiguiente' => $diaSiguiente,
        ];
    }
    

}
