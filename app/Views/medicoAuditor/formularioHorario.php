<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios del Médico</title>
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
<main class="content">
    <div style="width: 30%; text-align: center; margin: auto; border: 1px solid black; padding: 10px; background-color: #f2f2f2; border-radius: 5px;">
    <div class="form-container">
    <h4 class="card-title texto-blanco azul borde mb-2">Agregar Horario</h4>
        
            <form action="<?= site_url('medicos/informacion/' . $matricula . '/' . urlencode($dia) . '/guardar') ?>" method="post">
                <div class="mb-3">
                    <label for="diaSemana" class="form-label">Día de la Semana</label>
                    <input type="text" id="diaSemana" name="diaSemana" class="form-control" value="<?= esc($dia) ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="horaInicio" class="form-label">Hora de Inicio</label>
                    <input type="time" step="900" class="form-control" id="horaInicio" name="horaInicio">
                </div>
                <div class="mb-3">
                    <label for="horaFin" class="form-label">Hora de Fin</label>
                    <input type="time" class="form-control" id="horaFin" name="horaFin">
                </div>
                <div class="mb-3">
                    <label for="duracion">Duración</label>
                    <select name="duracion" id="duracion" class="form-control">
                        <option value="15">15 min</option>
                        <option value="30">30 min</option>
                        <option value="45">45 min</option>
                        <option value="60">60 min</option>
                    </select>
                </div>
                <div>
                    <button type="button" class="violeta" onclick="window.history.back()">Anterior</button>
                    <button type="submit" class="verde">Confirmar</button>
                    <a class="rojo" href="<?= base_url('/medicos') ?>">Cancelar</a>
                </div>
            </form>
        
    </div>
</div>
</main>
<?= $this->include('templates/footer'); ?>
</body>
</html>