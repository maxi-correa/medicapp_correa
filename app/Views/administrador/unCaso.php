<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedicApp - Inicio</title>
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

        <!-- ##########TABLA DATOS EMPLEADOS########## -->

    <div class="container">
    <div class="d-flex align-items-center justify-content-between encabezado ">
            <button class='rojo' onclick="location.href='<?= base_url('caso')?>'">Atrás</button>
            <div class="encabezado-blanco text-center texto-negro raleway fw-bold display-6 flex-grow-1">
                DATOS DEL EMPLEADO
            </div>
        </div>
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
    <div class="table-responsive-md">
        <table class="tabla-empleado">
            <thead>
                <tr>
                    <th rowspan="2">
                        <img class="img-tabla-empleado" src="<?= base_url('assets/img/icono_persona.png');?>" alt="">
                    </th>   
                    <?php foreach ($datosEmpleado as $unDato): ?>
                    <th rowspan="2"> <?php echo $unDato->empleado; ?></th>
                    <?php endforeach; ?>  
                    <th>PROPIO/FAMILIAR</th>
                    <th>FECHA DE INICIO DEL CASO</th>
                    <th>MOTIVO</th>
                    <th>GRAVEDAD</th>
                    <th>LUGAR DE REPOSO</th>
                </tr>
                <tr>
                <?php foreach ($datosEmpleado as $unDato): ?>
                    <th> <?php echo $unDato->tipoCertificado; ?> 
                        <?php if (isset($unDato->pacienteFamiliar)) {
                               echo "<br>".$unDato->pacienteFamiliar; 
                        } ?>
                    </th>
                    <th> <?php 
                    $fecha = new DateTime($unDato->fechaAusencia);
                    echo $fecha->format("d/m/Y");
                    ?></th>
                    <th> <?php echo $unDato->motivo; ?></th>
                    <th><?php echo $unDato->tiposeveridad; ?></th>
                    <th><?php echo $unDato->lugarReposo; ?></th>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>    
    </div>

        <!-- ##########TABLA DATOS CERTIFICADOS########## -->
    <div class="container">    
    <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">
      CERTIFICADOS
    </div>
    <div class="table-responsive-md">
    <table id="tableCertificado" class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 " >
        <thead id="table-header">
            <tr class="celeste">
                <th>ID</th>
                <th>DIAGNOSTICO</th>
                <th>DESDE</th>
                <th>HASTA</th>
                <th>LUGAR DE REPOSO</th>
                <th>CERTIFICADO</th>
                <th>CONDICIÓN</th>
                <th>MATRICULA</th>
                <th>MEDICO</th>
                
                <th>
                    <button class="toggle-tbody btn" onclick="desplegar('tableBodyCertificados');">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                        </svg>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody class="text-center" id="tableBodyCertificados">
           
        </tbody>
        <tfoot id="footerTableCertificado" class="gris">
        </tfoot>  
    </table>
    </div>
  </div>

  <!-- ##########TABLA DATOS SEGUIMIENTOS########## -->
  <div class="container mb-5">
    <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">
      SEGUIMIENTO
    </div>
    <div class="table-responsive-md">
    <table id="tableSeguimiento" class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 " >
        <thead id="table-header">
            <tr class="celeste">
                <th class="text-center">TURNO</th>
                <th class="text-center">LUGAR</th>
                <th class="text-center">MÉDICO</th>
                <th class="text-center">OBSERVACIÓN</th>
                <th class="text-center">RAZÓN DE LA OBSERVACIÓN</th>
                <th><button class="toggle-tbody btn" onclick="desplegar('tableBodySeguimiento');">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                </svg>
                </button></th>
            </tr>
        </thead>
        <tbody class="text-center" id="tableBodySeguimiento" >
        </tbody>
    </table>
    </div>
  </div>

    <?= $this->include('administrador/asignarEstado'); ?>
    <?= $this->include('templates/footer'); ?>

    </body>

    <script type="text/javascript">
    var baseUrl = '<?= base_url() ?>';
    var site_url = '<?= site_url() ?>'

    var legajo = <?=$datosEmpleado[0]->legajo?>; 
    var razonDelCertificado = "<?=$datosEmpleado[0]->tipoCertificado ?>";
    var paciente = "<?= $datosEmpleado[0]->tipoCertificado=='Familiar' ? $datosEmpleado[0]->pacienteFamiliar : $datosEmpleado[0]->empleado?>";
    var dni= "<?=$datosEmpleado[0]->dniPaciente?>";
    var btnRegistrarTurnoCMC = "<?=$display?>";
    var btnRegistrarTurnoTA = "<?=$displayTurnosActivo?>";
    
    var datosCertificados = <?php echo json_encode($datosCertificados); ?>;
    var datosSeguimientos = <?php echo json_encode($datosSeguimientos); ?>;
    </script>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js');?>"></script>
    
        <!-- Otros scripts -->
    <script src="<?= base_url('assets/js/dataTable/dataTable-2.1.8.js');?>"></script>
    <script src="<?= base_url('assets/js/tablas/botonDesplegar.js');?>"></script>
    <script src="<?= base_url('assets/js/moment/moment-2.30.1.js');?>"></script>

    <script src="<?= base_url('assets/js/tablas/asignarEstado.js')?>"></script>
    <script src="<?= base_url('assets/js/tablas/botonDesplegar.js')?>"></script>
    <script src="<?= base_url('assets/js/tablas/listarCertificadosAdministrador.js');?>"></script>
    <script src="<?= base_url('assets/js/tablas/listarSeguimientos.js');?>"></script>
    </html>


