<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Medicos Auditores</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css'); ?>">
    <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/layout.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/buttons.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/colores.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/divs.css">
    <!-- <link rel="stylesheet" href="/medicapp_dev/public/assets/css/tables.css"> -->
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/texto.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app-style-tabla.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dataTable.css'); ?>">
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

    <!--###################################################################################################################################-->
    <main class="content">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 azul text-center">
                <div class="texto-blanco font-weight-bold relaway text-uppercase" style="font-size: 28px;">Medicos Auditores</div>
            </div>

            <div class="col-12 col-md-10 col-lg-8">
                <div class="tabla-contenedor mt-0 mb-0">
                    <?php if (!empty($medicos)): ?>
                        <div class="text-end gris d-flex align-items-center" style="height: 3em;">
                            <div class="ms-auto pr-4">
                                <a class="verde" href="<?= base_url('medicos/crear') ?>" style="height: 45px;
    width: 50px;"><img src="<?= base_url('assets/img/agregar_medico.png') ?>" alt="agregar medico" style="width: 35px;">Crear</a>
                            </div>
                        </div>
                        <table id="tableListarMedicosAuditores" class="table table-hover">
                            <thead class="celeste texto-negro text-uppercase font-weight-bold relaway">
                                <tr>
                                    <th>Matricula</th>
                                    <th>Apellido</th>
                                    <th>Nombre</th>
                                    <th class="text-center"> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medicos as $medico): ?>
                                    <tr>
                                        <td><?= esc($medico['matricula']) ?></td>
                                        <td><?= esc($medico['apellido']) ?></td>
                                        <td><?= esc($medico['nombre']) ?></td>
                                        <td class="text-center">
                                            <a class="violeta me-1" href="<?= base_url('medicos/informacion/' . esc($medico['matricula'])) ?>">Informacion</a>

                                            <?php if ($medico['habilitado'] == 1): ?>
                                                <!-- Si el médico está habilitado, mostramos el botón de deshabilitar -->
                                                <a class="rojo me-1" href="<?= base_url('medicos/deshabilitar/' . esc($medico['matricula'])) ?>" id="btnDeshabilitar<?= esc($medico['matricula']) ?>">Deshabilitar</a>
                                            <?php elseif ($medico['habilitado'] == 0): ?>
                                                <!-- Si el médico está deshabilitado, mostramos el botón de habilitar -->
                                                <a class="verde me-1" href="<?= base_url('medicos/deshabilitar/' . esc($medico['matricula'])) ?>" id="btnHabilitar<?= esc($medico['matricula']) ?>">Habilitar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                    <?php else: ?>
                        <p class="text-center texto-rojo">No hay medicos auditores disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        //var_dump($medicos);

        ?>
    </main>
    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 medicapp_dev. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/dataTable/dataTable-2.1.8.js'); ?>"></script>

    <script src="<?= base_url('assets/js/paginadoListar/paginadoListarMedicos.js'); ?>"></script>

    <script>

    </script>


</body>

</html>