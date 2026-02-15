<?php

namespace App\Services;

use App\Models\CasoModel;
use Carbon\Carbon;

class CasoService
{
    protected CasoModel $casoModel;

    public function __construct()
    {
        $this->casoModel = new CasoModel();
    }

    public function cerrarCasosVencidos(): void //void porque no devuelve nada, solo actualiza el estado de los casos
    {
        $hoy = Carbon::today(); // Obtener la fecha actual

        $casos = $this->casoModel
            ->whereIn('idEstado', [1, 2]) // Pendiente y Activo
            ->where('fechaFin <', $hoy)
            ->findAll();

        foreach ($casos as $caso) {
            $this->casoModel->update($caso['numeroTramite'],['idEstado' => 3] // Finalizado
            );
        }
    }
}
