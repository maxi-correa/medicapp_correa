<!DOCTYPE html>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Inicio</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css');?>" >
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css');?>" >

        <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Enlaces a los archivos CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/variables.css');?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/layout.css');?>">
 
        <link rel="stylesheet" href="<?= base_url('assets/css/buttons.css');?>">

        <link rel="stylesheet" href="<?= base_url('assets/css/colores.css');?>">
        <!-- <link rel="stylesheet" href="<?= base_url('assets/css/tables.css')?>"> -->
        <!-- <link rel="stylesheet" href="<?= base_url('assets/css/app-style-tabla.css')?>"> -->
        <!-- <link rel="stylesheet" href="<?= base_url('assets/css/divs.css')?>"> -->
        <link rel="stylesheet" href="<?= base_url('assets/css/texto.css');?>">
</head>

<body>
    <header class="header" style="margin-bottom: 5%;">
        <?= view('templates/menu'); ?>
    </header>

    <main class="container">
        <div class="contenido-columna">
            <h1 class="texto-negro text-center text-uppercase">Lista de Turnos</h1>
                        
            <!-- Navegación entre semanas -->
             <div class="container gris d-flex justify-content-center align-items-center" style="height: 4em;">
                <div class="navegacion-semanas mb-3 d-flex justify-content-center align-items-center" style="margin: 0 !important;">
                    <?php if ($semanaOffset > 0): ?>
                        <a href="<?= site_url('turnos/listar/' . ($semanaOffset - 1)) ?>" class="btn verde texto-blanco font-weight-bold mr-4">← Anterior</a>
                    <?php endif; ?>
                    <a href="<?= site_url('turnos/listar/' . ($semanaOffset + 1)) ?>" class="btn verde texto-blanco font-weight-bold">Siguiente →</a>
                </div>
             </div>
            
            <!-- Aquí comienza la creación de tablas por día de la semana -->
            <?php 
                use Carbon\Carbon;
                Carbon::setLocale('es'); 
                $fechaActual = Carbon::today();
                foreach ($turnosPorDia as $fecha => $turnosDelDia): 
                $fechaCarbon = CArbon::parse($fecha);
                $diaSemana = $fechaCarbon->translatedFormat('l');
                $fechaFormateada = $fechaCarbon->format('d/m/Y');
                ?>
                <div class=" violeta w-100 relaway text-uppercase">
                    <div class="texto-blanco font-weight-bold relaway ml-3" style="margin:0; font-size: 28px;"><img src="<?= base_url('assets/img/calendario1.png') ?>" alt="icono de calendario" style="width: 30px;" class="mr-3 mb-1"><?= ucfirst($diaSemana) ?> - <?=$fechaFormateada ?>
                    
                    </div>
                </div>
                <div class="tabla-contenedor">
                    <table class="table table-hover text-center texto-negro mb-5">
                        <thead>
                            <tr class="celeste">
                                <th>NOMBRE DEL PACIENTE</th>
                                <th>HORA</th>
                                <th>DIRECCIÓN</th>                     
                                <th>MOTIVO</th>                     
                                <th>INFORMACION TURNO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($turnosDelDia)): ?>
                                <?php foreach ($turnosDelDia as $turno): ?>
                                    <tr>
                                        <td><?= isset($turno->paciente) ? esc($turno->paciente) : 'N/A' ?></td>
                                        <td><?= isset($turno->hora) ? \Carbon\Carbon::parse($turno->hora)->format('H:i') : 'N/A' ?></td>
                                        <td><?= isset($turno->direccion) ? esc($turno->direccion) : 'N/A' ?></td>
                                        <td><?= isset($turno->motivo) ? esc($turno->motivo) : 'N/A' ?></td>
                                        
                                        <td>
                                            <?php if ($fechaCarbon->greaterThanOrEqualTo($fechaActual)): ?>
                                                <button class="verde btn-ver-turno font-weight-bold"  onclick="window.location.href='<?= base_url('guardarIdTurno') . '/' . esc($turno->idTurno) ?>'"> Ver Turno </button>
                                            <?php else: ?>
                                                <span class="text-muted">No disponible</span>
                                            <?php endif; ?>    
                                            </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No hay turnos para esta fecha.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?= view('templates/footer'); ?>
    
    <script src="<?= base_url('assets/js/tablas/verTurno.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?= base_url('assets/js/tablas/botonDesplegar.js');?>"></script>
</body>

</html>
