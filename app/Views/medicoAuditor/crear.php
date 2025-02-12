<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Crear Medico Auditor</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/layout.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/buttons.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/colores.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/divs.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/form.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/texto.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/alerts.css">

</head>

<body>
    <header class="header">
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
        <div class="contenido-centrado">
            <div class="form-container">
                <h4 class="card-title texto-blanco azul borde mb-2">Registrar Medico Auditor</h4>
                <form id="formulario1" method="post" action="<?= base_url('medicos/guardar')?>">
                    <div class="contenido-centrado">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active texto-azul" id="medico-tab" data-bs-toggle="tab" href="#medico" role="tab" aria-controls="medico" aria-selected="true">Datos del Médico</a>
                        </li>
                        <!--<li class="nav-item" role="presentation">
                            <a class="nav-link  texto-azul" id="horario-tab" data-bs-toggle="tab" href="#horario" role="tab" aria-controls="horario" aria-selected="false">Horario</a>
                        </li>-->
                    </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="medico" role="tabpanel" aria-labelledby="medico-tab">
                            <div class="row justify-content-center">
                                <div>
                                    <div class="form-group mb-2">
                                        <label for="matricula">Matricula</label>
                                        <input type="number" class="form-control" id="matricula" name="matricula" required >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="especialidad">Especialidad</label>
                                        <input type="text" class="form-control" id="especialidad" name="especialidad"  >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="apellido">Apellido</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido"  >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre"  >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="dni">DNI</label>
                                        <input type="text" class="form-control" id="dni" name="dni"  >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="fechaNacimiento">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento"  >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="gmail">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="gmail" name="gmail"  >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="telefono">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono"  >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="contrasenia">Contraseña</label>
                                        <input type="password" class="form-control" id="contrasenia" name="contrasenia"  >
                                    </div>
                                    <div class="contenido-centrado mt-3">
                            <!--<button type="button" class="violeta" onclick="mostrarHorario()">Cargar Horarios</button>-->
                            <button type="submit" class="verde">Cargar Horarios</button>
                            <a class="rojo" href="<?= base_url('/medicos') ?>">Cancelar</a>
                        </div>
                                </div>
                            </div>
                        </div>

                        <!--<div class="tab-pane fade" id="horario" role="tabpanel" aria-labelledby="horario-tab">
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-10 col-lg-8 mb-3">
                                    
                                    <div class="form-group mb-2">
                                        <label for="diaSemana">Día de la Semana</label>
                                        <select id="diaSemana" name="diaSemana" >
                                            <option value="Lunes">Lunes</option>
                                            <option value="Martes">Martes</option>
                                            <option value="Miércoles">Miércoles</option>
                                            <option value="Jueves">Jueves</option>
                                            <option value="Viernes">Viernes</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="horaInicio">Hora de Inicio</label>
                                        <input type="time" step="900" class="form-control" id="horaInicio" name="horaInicio" >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="horaFin">Hora de Fin</label>
                                        <input type="time"  class="form-control" id="horaFin" name="horaFin" >
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="duracion">Duración</label>
                                        <select name="duracion" id="duracion">
                                        <option value="15">15 min</option>
                                        <option value="30">30 min</option>
                                        <option value="45">45 min</option>
                                        <option value="60">60 min</option>
                                    </select>
                                    </div>
                                    <div class="contenido-centrado">
                            <button type="button" class="violeta" onclick="mostrarMedico()">Anterior</button>
                            <button type="submit" class="verde">Confirmar</button>
                            <a class="rojo" href="<?= base_url('/medicos') ?>">Cancelar</a>
                        </div>
                                </div>
                            </div>
                        </div>-->
                    </div>

                    
                </form>
            </div>
        </div>
    </main>
    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 medicapp. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function mostrarHorario() {
        var horarioTab = document.querySelector('#horario-tab');
        var horarioPane = document.querySelector('#horario');
        var medicoTab = document.querySelector('#medico-tab');
        var medicoPane = document.querySelector('#medico');
        
        horarioTab.classList.add('active');
        horarioPane.classList.add('show', 'active');
        medicoTab.classList.remove('active');
        medicoPane.classList.remove('show', 'active');
    }

    function mostrarMedico() {
        var horarioTab = document.querySelector('#horario-tab');
        var horarioPane = document.querySelector('#horario');
        var medicoTab = document.querySelector('#medico-tab');
        var medicoPane = document.querySelector('#medico');
        
        medicoTab.classList.add('active');
        medicoPane.classList.add('show', 'active');
        horarioTab.classList.remove('active');
        horarioPane.classList.remove('show', 'active');
    }
</script>


</body>

</html>