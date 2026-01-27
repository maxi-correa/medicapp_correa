<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Seguimiento</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/layout.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/buttons.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/colores.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/alerts.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/divs.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/form.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/tables.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/texto.css">
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
                <h4 class="card-title texto-blanco azul borde mb-2">Registrar Seguimiento</h4>
                <form id="formulario1" method="POST" action="<?= base_url('seguimiento') ?>">
                    <div class="info borde mt-2 mb-2 p-2">
                        <?php if (!empty($fechas)): ?>
                            <p><strong>Fecha Fin del Caso: </strong><?= esc(DateTime::createFromFormat("Y-m-d", $fechas['fechaFin'])->format("d/m/Y")) ?></p>
                        <?php else: ?>
                            <p><strong>No hay datos del caso.</strong></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="tipoSeguimiento">Tipo de Seguimiento:</label>
                        <?php if (!empty($valoresEnum)): ?>
                            <select name="tipoSeguimiento" id="tipoSeguimiento">
                                <option value="">Seleccione un tipo de seguimiento</option>
                                <?php foreach ($valoresEnum as $valor): ?>
                                    <option value="<?= esc($valor) ?>"><?= esc($valor) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <select name="tipoSeguimiento" id="tipoSeguimiento" disabled>
                                    <option value="" selected>No hay tipos de seguimiento disponibles.</option>
                                <?php endif; ?>
                                </select>
                                <span class="mt-2 error-message" id="error-tipoSeguimiento"></span>
                    </div>
                    <div class="form-group">
                        <label for="fechaSeguimiento">Fecha Actualizada: </label>
                        <input type="date" id="fechaSeguimiento" name="fechaSeguimiento" disabled>
                        <span class="mt-2 error-message" id="error-FechaSeguimiento"></span>
                        <div id="mensaje-error" class="alert bg-warning border border-warning text-dark mt-2 d-none" role="alert" style="border-width: 2px;">
                            <span id="mensajeErrorTexto"></span>
                        </div>
                    </div>

                    <div class="card my-4" id="certificados-container" style="display: none;">
                        <div for="certificado" class="card-header celeste texto-blanco borde">
                            <label class="mb-0">Certificado </label>
                        </div>
                        <div class="card-body mb-1">
                            <?php if (!empty($certificado)): ?>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <p>
                                            <strong>Desde: </strong><?= esc(DateTime::createFromFormat("Y-m-d", $certificado['fechaEmision'])->format("d/m/Y")) ?><br>
                                            <strong>Hasta: </strong><?= esc(DateTime::createFromFormat("Y-m-d", $certificado['diasOtorgados'])->format("d/m/Y")) ?><br>
                                            <strong>Enfermedad: </strong> <?= esc($enfermedad['nombre']) ?>
                                        </p>
                                    </li>
                                </ul>
                            <?php else: ?>
                                <p class="text-center texto-rojo">No hay certificado disponible</p>
                            <?php endif; ?>
                        </div>


                    </div>

                    <div class="form-group">
                        <label for="observacion">Observación:</label>
                        <textarea name="observacion" id="observacion" rows="4" col="1" maxlength="225" placeholder="Especifica la razón para este seguimiento..." oninput="actualizarContador()"></textarea>
                        <p id="contador">225 caracteres restantes</p>
                        <span class="error-message" id="error-observacion"></span>
                    </div>

                    <div class="contenido-centrado">
                        <button class="verde" type="submit">Confirmar</button>
                        <a class="rojo" href="<?= base_url('visualizarCasoM') ?>">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal de confirmación -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModal">&times;</span>
                <h2 class="borde texto-blanco azul">Resumen de Información</h2>
                <p class="borde" id="modal-mensaje"></p>
                <div class="contenido-centrado">
                    <button class="verde" id="confirmarEnvio">Confirmar Envío</button>
                </div>
            </div>
        </div>
    </main>
    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 Medicapp. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const fechaFin = "<?= $fechas['fechaFin'] ?>";
        const fechaActual = "<?= $fechas['fechaActual'] ?>";
        const diasCantidad = parseInt("<?= $fechas['diasDisponibles']['diasDisponibles'] ?>", 10);

        function actualizarContador() {
            const maxLength = 225;
            const observacion = document.getElementById("observacion");
            const contador = document.getElementById("contador");
            const caracteresRestantes = maxLength - observacion.value.length;
            contador.textContent = `${caracteresRestantes} caracteres restantes`;
        }

        document.getElementById('tipoSeguimiento').addEventListener('change', function() {
            const tipoSeguimiento = this.value;
            const fechaSeguimientoInput = document.getElementById('fechaSeguimiento');
            const fechaSeguimientoLabel = document.querySelector('label[for="fechaSeguimiento"]');
            const certificadosContainer = document.getElementById('certificados-container');
            const mensajeError = document.getElementById("mensaje-error");
            var mensajeErrorTexto = document.getElementById('mensajeErrorTexto');

            fechaSeguimientoInput.style.display = 'block';
            fechaSeguimientoLabel.style.display = 'block';
            certificadosContainer.style.display = 'none';
            fechaSeguimientoInput.disabled = true;
            fechaSeguimientoInput.value = '';
            mensajeError.classList.add('d-none');

            let minDate, maxDate;

            if (tipoSeguimiento === 'ALTA') {
                minDate = new Date(fechaActual);
                maxDate = new Date(fechaFin);
                fechaSeguimientoInput.disabled = true;
                let today = new Date();
            let day = String(today.getDate()).padStart(2, "0");
            let month = String(today.getMonth() + 1).padStart(2, "0"); 
            let year = today.getFullYear();

            // Formato compatible con input type="date"
            fechaSeguimientoInput.value = `${year}-${month}-${day}`;
            console.log(fechaSeguimientoInput.value);
                
            } else if (tipoSeguimiento === 'IRREGULAR') {
                fechaSeguimientoInput.style.display = 'none';
                fechaSeguimientoLabel.style.display = 'none';
                certificadosContainer.style.display = 'block';

            } else if (tipoSeguimiento === 'EXTENDER PLAZO') {

                if (diasCantidad === 0) {
                    fechaSeguimientoInput.disabled = true;
                    mensajeError.classList.remove('d-none');
                    mensajeError.style.display = 'block';
                    mensajeErrorTexto.textContent = "No tienes suficientes días disponibles para extender el plazo.";
                } else {
                    minDate = new Date(fechaFin);
                    minDate.setDate(minDate.getDate() + 1);
                    maxDate = new Date(fechaFin);
                    const diasAExtender = Math.min(diasCantidad, 5);
                    maxDate.setDate(maxDate.getDate() + diasAExtender);
                    fechaSeguimientoInput.disabled = false;
                    mensajeError.classList.add('d-none');
                    mensajeError.style.display = 'none';
                }

            } else if (tipoSeguimiento === 'PROXIMO TURNO') {
                if (fechaActual === fechaFin) {
                    fechaSeguimientoInput.disabled = true;
                    mensajeError.classList.remove('d-none');
                    mensajeError.style.display = 'block';
                    mensajeErrorTexto.textContent = "El caso FINALIZARA hoy, no se puede agregar un proximo turno.";
                } else {
                    fechaSeguimientoInput.disabled = false;
                    minDate = new Date(fechaActual);
                    minDate.setDate(minDate.getDate() + 1);
                    maxDate = new Date(fechaFin);
                    mensajeError.classList.add('d-none');
                    mensajeError.style.display = 'none';
                }
            }

            if (minDate && maxDate) {
                fechaSeguimientoInput.min = minDate.toISOString().split('T')[0];
                fechaSeguimientoInput.max = maxDate.toISOString().split('T')[0];
            }
        });

        document.getElementById("formulario1").addEventListener("submit", function(event) {
            event.preventDefault();
            let valid = true;

            const tipoSeguimiento = document.getElementById("tipoSeguimiento").value.trim();
            const fechaActualizada = document.getElementById("fechaSeguimiento").value.trim();
            console.log(fechaActualizada);
            const observacion = document.getElementById("observacion").value.trim();
            const certificadosContainer = document.getElementById('certificados-container');

            const errorSeguimiento = document.getElementById("error-tipoSeguimiento");
            if (tipoSeguimiento === "") {
                errorSeguimiento.textContent = "Por favor, seleccione un tipo de seguimiento.";
                errorSeguimiento.classList.add('show');
                valid = false;
            } else {
                errorSeguimiento.textContent = "";
                errorSeguimiento.classList.remove('show');
            }

            const errorFecha = document.getElementById("error-FechaSeguimiento");
            if (tipoSeguimiento !== 'IRREGULAR' && fechaActualizada === "") {
                errorFecha.textContent = "Por favor, seleccione una fecha.";
                errorFecha.classList.add('show');
                valid = false;
            } else {
                errorFecha.textContent = "";
                errorFecha.classList.remove('show');
            }

            const errorObservacion = document.getElementById("error-observacion");
            if (observacion === "") {
                errorObservacion.textContent = "Por favor, complete la observación.";
                errorObservacion.classList.add('show');
                valid = false;
            } else {
                errorObservacion.textContent = "";
                errorObservacion.classList.remove('show');
            }

            if (valid) {
                const selectedTipo = document.getElementById("tipoSeguimiento");
                const selectedTipoText = selectedTipo.options[selectedTipo.selectedIndex].text;
                const fechaObj = new Date(fechaActualizada + "T00:00:00");
                const fechaFormateada = fechaObj.toLocaleDateString("es-AR");
                const mensaje = `
            <p><strong>Tipo de Seguimiento: </strong>${selectedTipoText}</p>
            <p><strong>Fecha Actualizada: </strong>${tipoSeguimiento === 'IRREGULAR' ? 'No aplica' : fechaFormateada}</p>
            <p><strong>Observación: </strong>${observacion}</p>`;

                mostrarModal(mensaje);
            }
        });

        function mostrarModal(mensaje) {
            document.getElementById("modal-mensaje").innerHTML = mensaje;
            const modal = document.getElementById("modal");
            modal.style.display = "block";
        }

        document.getElementById("closeModal").onclick = function() {
            document.getElementById("modal").style.display = "none";
        };

        window.onclick = function(event) {
            const modal = document.getElementById("modal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };

        document.getElementById("confirmarEnvio").onclick = function() {
            document.getElementById("formulario1").submit();
        };
    </script>
    </script>
</body>

</html>