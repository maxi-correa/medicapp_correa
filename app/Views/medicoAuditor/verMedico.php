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
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/layout.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/buttons.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/colores.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/divs.css">
    <!-- <link rel="stylesheet" href="/medicapp_correa/public/assets/css/tables.css"> -->
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/texto.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app-style-tabla.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dataTable.css'); ?>">

    <style>
        .container-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            /* Dos columnas de igual ancho */
            grid-template-rows: repeat(2, auto);
            /* Dos filas automáticas */
            gap: 20px;
            /* Espacio entre los elementos */
        }

        .grid-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /* Asegura que los botones estén abajo */
            align-items: center;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            height: 100%;
            /* Ocupa toda la altura disponible en el grid */
        }

        h1,
        h2,
        h3 {
            text-align: center;
        }

        .deshabilitarContenedor {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
        }

        #mensajeResultado {
            margin-top: 25px;
        }

        .modal-backdrop {
            display: none !important;
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

    <!--###################################################################################################################################-->
    <main class="content">
        <div class="container-grid">
            <!-- Primer cuadrante: Detalles del Médico -->
            <div class="grid-item">
                <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">Detalles del Médico</div>
                <table border="1" style="width: 100%; border-collapse: collapse;" class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 ">
                    <thead>
                        <tr class="celeste">
                            <th>Matricula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr>
                            <td><?= esc($medico['matricula']); ?></td>
                            <td><?= esc($medico['nombre']); ?></td>
                            <td><?= esc($medico['apellido']); ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php if ($medico): ?>
                    <ul>

                    </ul>
                <?php else: ?>
                    <p>No se encontraron datos del médico.</p>
                <?php endif; ?>
            </div>

            <!-- Segundo cuadrante: Horarios de Trabajo -->
            <div class="grid-item">
                <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">Horarios de Trabajo</div>
                <?php if (!empty($horariosMed)): ?>
                    <table border="1" style="width: 100%; border-collapse: collapse;" class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 ">
                        <thead>
                            <tr class="celeste">
                                <th>Día de la Semana</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Duración (minutos)</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php foreach ($horariosMed as $horario): ?>
                                <tr>
                                    <td><?= esc($horario->diaSemana); ?></td>
                                    <td><?= esc($horario->horaInicio); ?></td>
                                    <td><?= esc($horario->horaFin); ?></td>
                                    <td><?= esc($horario->duracion); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay horarios asignados para este médico.</p>
                <?php endif; ?>
            </div>

            <!-- Tercer cuadrante: Turnos del Médico -->
            <div class="grid-item">
                <h3>Turnos del Médico</h3>
                <?php if (!empty($turnosMed)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Lugar</th>
                                <th>Motivo</th>
                                <th>Número de Trámite</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($turnosMed as $turno): ?>
                                <tr>
                                    <td><?= esc($turno['fecha']); ?></td>
                                    <td><?= esc($turno['hora']); ?></td>
                                    <td><?= esc($turno['lugar']); ?></td>
                                    <td><?= esc($turno['motivo']); ?></td>
                                    <td><?= esc($turno['numeroTramite']); ?></td>
                                    <td><?php switch (esc($turno['idEstado'])) {
                                            case '10':
                                                echo 'PENDIENTE';
                                                break;
                                            default:
                                                echo '';
                                                break;
                                        } ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay turnos disponibles para este médico.</p>
                <?php endif; ?>
            </div>

            <!-- Cuarto cuadrante: Botones de Acción -->
            <div class="grid-item">
                <div id="habilitarContenedor" class="habilitarContenedor">
                    <!--
                    <label for="cantidadDias">¿Cuántos días desea deshabilitarlo?</label>
                    <input type="number" id="cantidadDias" name="cantidadDias" placeholder="0" min="0">
                    -->
                    <div>
                        <div style="text-align: center;">
                            <label for="">CONFIRMAR DESHABILITACIÓN: </label>
                        </div>
                        <div>
                            <a class="rojo me-1" href="javascript:void(0);" id="btna" data-matricula="<?= $medico['matricula'] ?>" data-url="<?= base_url('medicos/deshabilitarMedico') ?>/<?= $medico['matricula'] ?>">DESHABILITAR PERMANENTEMENTE</a>
                            <a class="rojo me-1" href="javascript:void(0);" id="btnt">DESHABILITAR TEMPORALMENTE</a>
                            <button class='rojo me-1' onclick="location.href='<?= base_url('medicos'); ?>'">VOLVER ATRAS</button>
                        </div>
                    </div>
                </div>

                <div id="deshabilitarContenedor" class="deshabilitarContenedor">

                    <label for="">Confirmar habilitación: </label>
                    <a class="verde me-1" href="#" id="btnHabilitar" data-matricula="<?= $medico['matricula'] ?>" data-url="<?= base_url('medicos/habilitarMedico') ?>/<?= $medico['matricula'] ?>">Habilitar</a>
                    <button class='rojo me-1' onclick="location.href='<?= base_url('medicos'); ?>'">Cancelar</button>
                </div>
            </div>

        </div>

        <!-- Modal -->
        <div id="modalDeshabilitar" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Deshabilitar Temporalmente</h2>
                <label for="diasDeshabilitado">Cantidad de días:</label>
                <input type="number" id="diasDeshabilitado" min="1" placeholder="Ingrese días">
                <p id="errorMensaje" style="color: red; font-size: 14px; display: none;">Ingrese un número válido mayor a 0</p>
                <br>
                <button id="confirmarTemporal" class="verde me-1" data-matricula="<?= $medico['matricula'] ?>" data-url="<?= base_url('medicos/deshabilitarTurnos') ?>/<?= $medico['matricula'] ?>">Confirmar</button>
                <button id="cancelarTemporal" class="rojo me-1">Cancelar</button>
            </div>
        </div>



    </main>
    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 medicapp_correa. Todos los derechos reservados.</p>
    </footer>

    <script>
        var mensajeContenedor = document.getElementById('mensajeContenedor'); // Contenedor con id 'mensajeContenedor'

        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el valor de 'habilitado' del médico
            var habilitado = "<?= $medico['habilitado'] ?>"; // Obtener el valor de 'habilitado' desde PHP
            console.log('Valor de habilitado:', habilitado); // Verificar en la consola
            // Ocultar todos los contenedores
            document.getElementById('habilitarContenedor').style.display = 'none';
            document.getElementById('deshabilitarContenedor').style.display = 'none';

            console.log('habilitarContenedor:', document.getElementById('habilitarContenedor'));
            console.log('deshabilitarContenedor:', document.getElementById('deshabilitarContenedor'));

            // Mostrar el contenedor correspondiente según el valor de 'habilitado'
            if (habilitado == '0') {
                // El médico está habilitado, mostrar la opción para deshabilitar
                document.getElementById('deshabilitarContenedor').style.display = 'block';
            } else if (habilitado == '1') {
                // El médico está deshabilitado, mostrar la opción para habilitar
                document.getElementById('habilitarContenedor').style.display = 'block';
            }
        });


        document.getElementById('btnHabilitar').addEventListener('click', function(event) {
            event.preventDefault(); // Prevenir que el enlace navegue a otra página

            var matricula = this.getAttribute('data-matricula'); // Obtener la matrícula desde el atributo data-matricula
            var url = this.getAttribute('data-url'); // Obtener la URL desde el atributo data-url

            // Realizar la solicitud AJAX
            fetch(url)
                .then(response => {
                    // Verifica el status de la respuesta (por ejemplo, 200 OK)
                    console.log(response); // Depuración de la respuesta
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json(); // Convertir la respuesta en JSON
                })
                .then(data => {
                    console.log(data); // Depuración del JSON recibido
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("Hubo un error al habilitar al médico: " + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error); // Mostramos el error en consola
                    alert("Error en la solicitud: " + error.message);
                });
        });



        var modal = document.getElementById("modalDeshabilitar");
        var btn = document.getElementById("btnt");
        var closeBtn = document.querySelector(".close");
        var cancelBtn = document.getElementById("cancelarTemporal");
        var confirmarBtn = document.getElementById("confirmarTemporal");
        var inputDias = document.getElementById("diasDeshabilitado");
        var errorMensaje = document.getElementById("errorMensaje");

        // Abrir la modal al hacer clic en el botón
        btn.addEventListener("click", function() {
            modal.style.display = "block";
        });

        // Cerrar la modal al hacer clic en la "X"
        closeBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });

        // Cerrar la modal al hacer clic en "Cancelar"
        cancelBtn.addEventListener("click", function() {
            modal.style.display = "none";
        });

        // Cerrar la modal si el usuario hace clic fuera de ella
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });

        // Cerrar la modal si el usuario hace clic fuera de ella
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });

        // Validar la entrada antes de confirmar
        confirmarBtn.addEventListener("click", function() {
            var dias = inputDias.value.trim(); // Eliminar espacios vacíos
            var matricula = confirmarBtn.getAttribute("data-matricula"); // Obtener la matrícula
            var url = "<?= base_url('medicos/deshabilitarMedicoTemporal') ?>"; // Ruta a la función en el backend

            console.log(matricula);
            console.log(dias);

            if (dias === "" || isNaN(dias) || parseInt(dias) <= 0) {
                errorMensaje.style.display = "block"; // Mostrar mensaje de error
            } else {
                errorMensaje.style.display = "none"; // Ocultar mensaje de error
                modal.style.display = "none"; // Cerrar modal si es válido

                // Enviar solicitud AJAX
                fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            matricula: matricula,
                            dias: parseInt(dias)
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Médico deshabilitado correctamente");
                            location.reload(); // Recargar la página
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error en la solicitud:", error);
                        alert("Hubo un problema al deshabilitar al médico.");
                    });
            }
        });
    </script>
    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->

    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/dataTable/dataTable-2.1.8.js'); ?>"></script>
    <script src="<?= base_url('assets/js/paginadoListar/paginadoListarMedicos.js'); ?>"></script>
    <script src="<?= base_url('assets/js/verMedicoJS/script.js'); ?>"></script>


</body>

</html>