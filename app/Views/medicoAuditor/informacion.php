<!DOCTYPE html>
<html lang="es">
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
    <!--#########VISTA DE LA PAGINA################################## !-->
    
    <div class="container">
        <div class="container" style="display: flex; justify-content: space-between;">
        <div style="text-align: left;">
        <a href="<?= site_url('medicos') ?>">
            <button class='rojo btn-accion'>
                Atrás
            </button>
        </a>
        </div>
        <div style="text-align: right;">
            <h5><b>Nombre y Apellido: </b><?php echo esc($nombre['nombre']); ?> <?php echo esc($nombre['apellido']);?></h5>
        </div>
        </div>
        <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">
            HORARIOS DEL MÉDICO: <?= esc($matricula) ?>
        </div>
        <div class="table-responsive-md">
    <table class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 ">
        <thead>
            <tr class="celeste">
                <th>DÍA DE LA SEMANA</th>
                <th>HORARIO</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php foreach ($horarios as $horario): ?>
                <tr>
                    <td><h5><?= esc($horario['diaSemana']) ?></h5></td>
                    <td>
                        <?php if (!empty($horario['horaInicio']) && !empty($horario['horaFin'])): ?>
                            <a href="<?= site_url('medicos/informacion/' . $horario['matricula'] . '/' . eliminarTildes($horario['diaSemana'])) ?>">
                                <button class='verde btn-accion btn-redirigir'>VER</button>
                            </a>
                        <?php else: ?>
                            <a href="<?= site_url('medicos/informacion/' . (isset($horario['matricula']) ? $horario['matricula'] : $matricula) . '/' . eliminarTildes($horario['diaSemana']) . '/vacio') ?>">

                                <button class="gris-oscuro btn-accion btn-redirigir">VACÍO</button>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        </div>
</div>
<?= $this->include('templates/footer'); ?>
</body>
</html>

<!--"background-color: gray; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;"