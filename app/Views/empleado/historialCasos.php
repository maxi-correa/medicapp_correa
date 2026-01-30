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
                    <?php endif; ?><li class="nav-item">
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
    <main>
        <div class="container">
            <div class="relaway text-center">
                <h1>HISTORIAL DE CASOS</h1>
            </div>
        </div>
        <div class="container" style="display: flex; justify-content: space-between;">
            <div style="text-align: left;">
                <b>Legajo:</b> <?php echo $legajo ?><br>
                <b>Empleado/a:</b> <?php echo $nombre . " " . $apellido ?><br>
                <?php if (!empty($casosPendientes) or !empty($casosActivos)): ?>
                    <p>Existe un caso <b>en curso</b>. <button class='verde btn-accion btn-redirigir' onclick="location.href='<?= base_url('visualizarCasoE') ?>'">Ir al caso</button> </p>
                    <?php else: ?>
                        <p>No existen casos en curso.</p>
                <?php endif; ?>
            </div>
            <div style="text-align: right;">
                <button class='rojo' onclick="location.href='<?= base_url('perfil') ?>'">Atrás</button>
            </div>
        </div>

        <!-- TABLA DE CASOS PENDIENTES (NO EXISTIRÁ POR SUGERENCIA DE CORRECCIÓN)!-->
        <!-- 
                    
        <div class="container">
            <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">
                CASO PENDIENTE
            </div>
            <div class="table-responsive-md">

                <table id="tableEmpleado" class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 ">
                    <thead id="table-header">
                        <tr class="celeste">
                            <th>NRO TRÁMITE</th>
                            <th>CORRESPONDE A</th>
                            <th>FECHA INICIADO</th>
                            <th>DISPONE DE CERTIFICADO</th>
                            <?php //if (!empty(array_filter($casosPendientes, fn($caso) => $caso['idCertificado'] !== null))): ?>
                                <th>SEVERIDAD</th>
                                <th>CERTIFICADO</th>
                            <?php //endif; ?>
                            <th>VER CASO</th>
                            <th>
                                <button class="toggle-tbody btn" onclick="desplegar('tableBodyPendientes');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                    </svg>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="tableBodyPendientes">
                        <?php if ($casosPendientes): ?>
                            <?php foreach ($casosPendientes as $caso): ?>
                                <tr>
                                    <td><?= $caso['numeroTramite']; ?></td>
                                    <td><?= $caso['tipoCertificado']; ?></td>
                                    <td><?= DateTime::createFromFormat("Y-m-d", $caso['fechaAusencia'])->format("d/m/Y"); ?></td>
                                    <td><?= $caso['disponeCertificado']; ?></td>
                                    <td>
                                        <?php if ($caso['tiposeveridad'] !== null): ?>
                                            <?= $caso['tiposeveridad']; ?>
                                        <?php else: ?>
                                            ---
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($caso['idCertificado'] != null): ?>
                                            <a id='btn-descargar-certificado' class='bg-transparent' href="<?php echo base_url('download/' . $caso['legajo'] . '/' . $caso['idCertificado']) ?>"> <img class='img_asignar_estado' src="<?php echo base_url('assets/img/icono_descargar_certificado.png') ?>"> </a>
                                        <?php else: ?>
                                            ---
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class='verde btn-accion btn-redirigir' onclick="location.href='<?= base_url('visualizarCasoE') ?>'">Ver</button>
                                    </td>
                                    <td></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No hay casos pendientes</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div> FIN Tabla responsiva !-->
        <!-- TABLA DE CASOS ACTIVOS (NO EXISTIRÁ POR SUGERENCIA DE CORRECIÓN)!-->
        <!--
        <div class="container">
            <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">
                CASO ACTIVO
            </div>
            <div class="table-responsive-md">

                <table id="tableEmpleado" class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 ">
                    <thead id="table-header">
                        <tr class="celeste">
                            <th>NRO TRÁMITE</th>
                            <th>CORRESPONDE A</th>
                            <th>FECHA INICIADO</th>
                            <th>FECHA FIN</th>
                            <th>DISPONE DE CERTIFICADO</th>
                            <th>SEVERIDAD</th>
                            <th>CERTIFICADO</th>
                            <th>
                                <button class="toggle-tbody btn" onclick="desplegar('tableBodyActivos');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                    </svg>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="tableBodyActivos">
                        <?php if ($casosActivos): ?>
                            <?php foreach ($casosActivos as $caso): ?>
                                <tr>
                                    <td><?= $caso['numeroTramite']; ?></td>
                                    <td><?= $caso['tipoCertificado']; ?></td>
                                    <td><?= DateTime::createFromFormat("Y-m-d", $caso['fechaAusencia'])->format("d/m/Y"); ?></td>
                                    <td><?= DateTime::createFromFormat("Y-m-d", $caso['fechaFin'])->format("d/m/Y"); ?></td>
                                    <td><?= $caso['disponeCertificado']; ?></td>
                                    <td><?= $caso['tiposeveridad']; ?></td>
                                    <td>
                                        <?php if ($caso['idCertificado'] != null): ?>
                                            <a id='btn-descargar-certificado' class='bg-transparent' href="<?php echo base_url('download/' . $caso['legajo'] . '/' . $caso['idCertificado']) ?>"> <img class='img_asignar_estado' src="<?php echo base_url('assets/img/icono_descargar_certificado.png') ?>"> </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class='verde btn-accion btn-redirigir' onclick="location.href='<?= base_url('visualizarCasoE') ?>'">Ver</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No hay casos activos</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
            </div> FIN Tabla responsiva !-->

        <!-- TABLA DE CASOS FINALIZADOS !-->
        <div class="container">
            <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">
                CASOS FINALIZADOS
            </div>
            <div class="table-responsive-md">

                <table id="tableEmpleado" class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 ">
                    <thead id="table-header">
                        <tr class="celeste">
                            <th>CASO NÚMERO</th> <!-- Nueva columna para el contador -->
                            <th>NRO TRÁMITE</th>
                            <th>RAZÓN DE CERTIFICADO</th>
                            <th>FECHA FINALIZADO</th>
                            <th>DISPONE DE CERTIFICADO</th>
                            <th>CERTIFICADO</th>
                            <th>ESTADO DEL CERTIFICADO</th>
                            <th>SEVERIDAD</th>
                            <th>TURNO</th>
                            <th><button class="toggle-tbody btn" onclick="desplegar('tableBodyFinalizados');">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                    </svg>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="tableBodyFinalizados">
                        <?php if ($casosFinalizados): ?>
                            <?php $contador = 0; // Inicializamos un contador 
                            ?>
                            <?php foreach ($casosFinalizados as $caso): ?>
                                <tr>       
                                    <td><?= $contador++; ?></td> <!-- Mostramos el número del caso -->
                                    <td><?= $caso['numeroTramite']; ?></td>
                                    <td><?= $caso['tipoCertificado']; ?></td>
                                    <td><?= DateTime::createFromFormat("Y-m-d", $caso['fechaFin'])->format("d/m/Y"); ?></td>
                                    <td><?= $caso['disponeCertificado']; ?></td>
                                    <td>
                                        <?php if ($caso['idCertificado'] != null): ?>
                                            <a id='btn-descargar-certificado' class='bg-transparent' href="<?php echo base_url('download/' . $caso['legajo'] . '/' . $caso['idCertificado']) ?>"> <img class='img_asignar_estado' src="<?php echo base_url('assets/img/icono_descargar_certificado.png') ?>"> </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $caso['idEstado'] == 4 ? 'Injustificado' : ($caso['idEstado'] == 5 ? 'Justificado' : ''); ?>
                                    </td>
                                    <td><?= $caso['tiposeveridad']; ?></td>
                                    <?php if ($caso['tiposeveridad'] === 'Complejo') {
                                        echo "<td>SI</td>";
                                }else {
                                    echo "<td>NO</td>";
                                }
                                    ?>
                                    <td></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10">No hay casos finalizados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div> <!-- FIN Tabla responsiva !-->
        </div>
    </main>

    <?= $this->include('templates/footer'); ?>

</body>
<script type="text/javascript">
    var baseUrl = '<?= base_url() ?>';
    var site_url = '<?= site_url() ?>'
</script>
<!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>

<!-- Otros scripts -->
<script src="<?= base_url('assets/js/dataTable/dataTable-2.1.8.js'); ?>"></script>
<script src="<?= base_url('assets/js/tablas/botonDesplegar.js'); ?>"></script>
<script src="<?= base_url('assets/js/moment/moment-2.30.1.js'); ?>"></script>

<script src="<?= base_url('assets/js/tablas/botonDesplegar.js') ?>"></script>
<script src="<?= base_url('assets/js/tablas/listarCasosFinalizados.js'); ?>"></script>

</html>