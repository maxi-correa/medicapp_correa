<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Inicio</title>
    <?= view('templates/link'); ?>
    <style>
        .verde.disabled-link {
            pointer-events: none;
            cursor: not-allowed;
            color: black;
            opacity: 0.3;
        }
    </style>
</head>

<body>
    <header class="header" style="margin-bottom: 5%;">
        <?= view('templates/menu'); ?>
    </header>

    <!--###################################################################################################################################-->
    <main class="content">
        <div class="row justify-content-center">
            <div class="col-8 ">
                <div class="text-center">
                    <h1 class="texto-blanco azul text-uppercase">Sistema de Medicina Laboral</h1>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <img class="" src="assets\img\logo.png" width="230" alt="Logo medicapp_correa">
                </div>
                
                <div class="d-flex flex-column align-items-center">
                    <a class="verde w-50 mb-3 <?= $disable_registrar ? 'disabled-link' : '' ?>"
                        href="<?= base_url('registrar-caso') ?>"
                        <?= $disable_registrar ? 'tabindex="-1"' : '' ?>
                        <?= $disable_registrar ? 'onclick="return false;"' : '' ?>>Registrar Caso</a>

                    <a class="verde w-50 <?= $disable_ver_caso ? 'disabled-link' : '' ?>"
                        href="<?= base_url('visualizarCasoE') ?>"
                        <?= $disable_ver_caso ? 'tabindex="-1"' : '' ?>
                        <?= $disable_ver_caso ? 'onclick="return false;"' : '' ?>>Ver Mi Caso Actual</a>
                </div>
            </div>
        </div>
    </main>
    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <?= view('templates/footer'); ?>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>

</body>

</html>