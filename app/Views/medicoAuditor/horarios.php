<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css'); ?>">

    <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlace al css de dataTables -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dataTable.css'); ?>">

    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/variables.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/layout.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/buttons.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/colores.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/texto.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/app-style-menu.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/app-style-tabla.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/app-style-ventana-modal.css') ?>">
    <style>
        .encabezado {
            display: flex;
            align-items: center;
        }
        .encabezado a {
            margin-right: auto;
        }
        .encabezado .encabezado-blanco {
            flex-grow: 1;
            text-align: center;
        }
        .formatTd{
            text-align: right;
        }
    </style>
</head>

<body>
    <header class="header" style="margin-bottom: 5%;">
        <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
            <h1 class="navbar-brand ms-3">MEDICAPP</h1>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php
                    $menu = session()->get('menu');
                    if (!empty($menu)):
                        foreach ($menu as $item): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $item['url'] ?>"><?= $item['label'] ?></a>
                            </li>
                        <?php endforeach;
                    else: ?>
                        <li class="nav-item"><a class="nav-link">No hay opciones de menú disponibles.</a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('misHorarios') ?>">Mis Horarios</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link"><?php echo session()->get('nombre'); ?> <?php echo session()->get('apellido'); ?></span>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link"><?php echo session('rol'); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('salir') ?>"><i class="fas fa-sign-out-alt"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!--CONTENIDO DE LA VISTA-->

    <main>
        <div class="container">
            <button class='rojo' onclick="location.href='<?= base_url('turnos/listar')?>'">Atrás</button>
            <p></p>
            <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">
                MIS HORARIOS
            </div>
            <div class="table-responsive-md">
            <?php if (!empty($horarios)): ?>
                <table class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 ">
                    <thead>
                        <tr class="celeste">
                            <th>DÍA DE LA SEMANA</th>
                            <th>HORA INICIO</th>
                            <th>HORA FIN</th>
                            <th>DURACIÓN</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php foreach ($horarios as $horario): ?>
                            <tr>
                                <td><?= $horario['diaSemana'] ?></td>
                                <td class="formatTd"><?= $horario['horaInicio'] ?></td>
                                <td class="formatTd"><?= $horario['horaFin'] ?></td>
                                <td class="formatTd">
                                    <?php
                                    $duracion = $horario['duracion'];
                                    if ($duracion >= 60) {
                                        $hours = intdiv($duracion, 60);
                                        $remainingMinutes = $duracion % 60;
                                        echo $remainingMinutes > 0 ? "{$hours} h {$remainingMinutes} min" : "{$hours} h";
                                    } else {
                                        echo "{$duracion} min";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4"> No se encontraron horarios para este médico. </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="container" style="margin-top: 10%;">
            <h4 style=" text-align: center;">CONSULTE CON SU ADMINISTRATIVO PARA LA GESTIÓN HORARIA DE SU AGENDA</h4>
        </div>

    </main>

    <?= $this->include('templates/footer'); ?>

    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
</body>

</html>