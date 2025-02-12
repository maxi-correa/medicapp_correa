    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedicApp</title>
    <head>
        <!-- Enlace a Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css');?>" >
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css');?>" >
    
        <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- Enlace al css de dataTables -->
        <link rel="stylesheet" href="<?= base_url('assets/css/dataTable.css');?>">

        <!-- Enlaces a los archivos CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/variables.css');?>">
    
        <link rel="stylesheet" href="<?= base_url('assets/css/layout.css');?>">
 
        <link rel="stylesheet" href="<?= base_url('assets/css/buttons.css');?>">
    
        <link rel="stylesheet" href="<?= base_url('assets/css/colores.css');?>">
    
        <link rel="stylesheet" href="<?= base_url('assets/css/texto.css');?>">

        <link rel="stylesheet" href="<?= base_url('assets/css/app-style-menu.css');?>">
  
        <link rel="stylesheet" href="<?= base_url('assets/css/app-style-tabla.css');?>">

        <link rel="stylesheet" href="<?= base_url('assets/css/app-style-ventana-modal.css')?>">
    </head>
    

    <body>
    <header class="header margin-10">
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
                        <a class="nav-link" href="<?= base_url('salir')?>"><i class="fas fa-sign-out-alt"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
<!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
<script src="<?= base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js');?>"></script>
    
    <script src="<?= base_url('assets/js/dataTable/dataTable-2.1.8.js');?>"></script>
    <script src="<?= base_url('assets/js/tablas/botonDesplegar.js');?>"></script>
    <script src="<?= base_url('assets/js/moment/moment-2.30.1.js');?>"></script>

