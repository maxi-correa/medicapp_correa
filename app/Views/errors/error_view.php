<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Error de Acceso</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/buttons.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/colores.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/texto.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/divs.css">
</head>

<body class="gris">
    <div class="container text-center mt-5 col-8 borde">
        <h1 class="display-4 borde rojo texto-blanco mt-2 mb-2"><strong>Error de Acceso</strong></h1>
        <h2 class="lead ">Lo sentimos, no tienes permisos para acceder a esta página.</h2>
        <img id="imagen" src="<?= base_url('assets/img/403.jpg') ?>" alt="Imagen" width="450" class="img-fluid mb-3 borde">
        <br>
        <?php if (session()->get('isLoggedIn')): ?>
            <a href="javascript:history.back()" class="violeta">Volver a la página anterior</a>
            <a href="<?= base_url('salir')?>" class="violeta">Volver al inicio</a>
        <?php else: ?>
            <a href="<?= base_url('salir')?>" class="violeta">Volver al inicio</a>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>