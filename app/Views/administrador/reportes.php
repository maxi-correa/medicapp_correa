<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Reportes</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css');?>" >
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css');?>" >
    <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/layout.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/buttons.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/colores.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/texto.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/divs.css">
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
    <main class="content ">
    <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 mb-2 azul text-center rounded-top">
                <div class="texto-blanco relaway text-uppercase font-weight-bold" style="font-size: 28px;">Reportes</div>
            </div>
        <div class="borde col-12 col-md-10 col-lg-8 py-5">
            <div class="row text-center d-flex align-items-stretch">
                <div class="col-md-4 mb-4 d-flex">
                    <?php
                        $fechaHoy = date('Y-m-d');
                        $fechaMesDespues = date('Y-m-d', strtotime('+1 month'));  
                        $matricula = 10000;
                    ?>  
                <a href="<?= base_url('reportes/ausentismo/'.$fechaHoy.'/'.$fechaMesDespues)?>"
                    class="violeta text-white p-4 rounded d-flex align-items-center w-100">
                        <div class="contenido-columna w-100">
                        <h3 class="fw-bold">AUSENTISMO</h3>
                        <p>Ausentismo del personal en un periodo dado.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4 d-flex">
                    <a 
                    href="<?= base_url('reportes/certificados-emitidos')?>"
                    class="violeta text-white p-4 rounded d-flex align-items-center w-100">
                        <div class="contenido-columna w-100">
                            <h3 class="fw-bold">MEDICOS</h3>
                            <p>Consulta de los certificados emitidos.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 mb-4 d-flex">
                    <a 
                    href="<?= base_url('reportes/certificados')?>"
                    class="violeta text-white p-4 rounded d-flex align-items-center w-100">
                        <div class="contenido-columna w-100">
                            <h3 class="fw-bold">CERTIFICADOS</h3>
                            <p>Cantidad de certificados presentados en un periodo dado.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- ############################################################################################################################### -->

    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 medicapp_dev. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js');?>"></script>

</body>

</html>