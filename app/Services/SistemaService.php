<?php

namespace App\Services;

use App\Services\CasoService;
use App\Services\TurnoService;

class SistemaService
{
    protected CasoService $casoService;
    protected TurnoService $turnoService;

    public function __construct()
    {
        $this->casoService  = new CasoService();
        $this->turnoService = new TurnoService();
    }

    public function ejecutarTareasAutomaticas(): void
    {
        $this->turnoService->cancelarTurnosCaducados();
        $this->casoService->cerrarCasosVencidos();
    }
}
