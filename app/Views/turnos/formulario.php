<!DOCTYPE html>
<html lang="es">

<?php
/** 
 * @var array  $caso
 * @var array  $empleado
 * @var array  $medicos
 * @var string $fecha
 * @var string $dia
 * @var int    $diasFaltantes
 */
?>
<!-- Lo anterior se conoce como PHPDoc y ayuda a los IDEs a proporcionar autocompletado y verificación de tipos. -->
<!-- IDE es un Entorno de Desarrollo Integrado, una aplicación que proporciona herramientas para facilitar la programación. -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Registro Turno</title>
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
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/tables.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/divs.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/form.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/texto.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/alerts.css">


</head>

<body>
    <header class="header">
        <?= view('templates/menu'); ?>
    </header>

    <!--###################################################################################################################################-->
    <main class="content">
        <div class="container mt-4 ">
            <div class="col ">
                <div class="row-md-6">
                    <div class="card mb-4 gris ">
                        <div class="card-body">
                            <h5 class="card-title texto-blanco azul borde">Información del Caso</h5>
                            <div class="table-responsive mt-1 mb-1 tabla-contenedor">
                                <table>
                                    <thead class="azul">
                                        <tr>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                            <th>Dias para Finalizar</th>
                                            <th>Apellido y Nombre</th>
                                            <th>Correponde</th>
                                            <th>Motivo</th>
                                            <th>Lugar de Reposo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($caso)): ?>
                                            <tr>
                                                <td><?= esc(DateTime::createFromFormat("Y-m-d", $caso['fechaInicio'])->format("d/m/Y")) ?></td>
                                                <td><?= esc(DateTime::createFromFormat("Y-m-d", $caso['fechaFin'])->format("d/m/Y")) ?></td>
                                                <td><?= $diasFaltantes ?></td>
                                                <td><?= esc($empleado['apellido']) . ' ' . esc($empleado['nombre']) ?></td>
                                                <td><?= esc($caso['corresponde']) ?></td>
                                                <td><?= esc($caso['motivo']) ?></td>
                                                <td><?= esc($caso['lugarReposo']) ?></td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="texto-rojo text-center">No hay informacion del caso.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row-md-6 mt-1">
                        <div class="card mb-4 gris ">
                            <div class="card-body mb-1 ">
                                <h5 class="card-title texto-blanco azul borde">Registrar Turno</h5>
                                <div class="info borde mt-2 mb-2 p-2">
                                    <p><strong>Fecha: </strong><?= esc(DateTime::createFromFormat("Y-m-d", $fecha)->format("d/m/Y")) ?></p>
                                    <p><strong>Día: </strong><?= esc($dia) ?></p>
                                </div>
                                <form class="borde" action="<?= base_url('validar') ?>" id=formulario1 method="post">
                                    <div class="form-group">
                                        <label class="mx-1" for="matricula">Médicos</label>
                                        <select class="form-select mb-1" name="matricula" id="matricula">
                                            <option value="">Seleccione un Médico</option>
                                            <?php foreach ($medicos as $medico): ?>
                                                <option value="<?php echo esc($medico['matricula']); ?>">
                                                    <?php echo esc($medico['matricula']); ?> | <?php echo esc($medico['apellido']); ?> <?php echo esc($medico['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="error-message" id="error-matricula"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="mx-1" for="horario">Horarios</label>
                                        <select class="form-select mb-1" name="horario" id="horario" style="max-height: 200px; overflow-y: scroll;" disabled>
                                            <option value="">Seleccione un Horario</option>
                                        </select>
                                        <span class="error-message" id="error-horario"></span>
                                    </div>
                                    <div class="contenido-centrado">
                                        <button class="verde" type="submit">Confirmar</button>
                                        <a class="rojo" href="<?= base_url('visualizarCasoA') ?>">Cancelar</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
    <?= view('templates/footer'); ?>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const baseUrl = '<?= base_url() ?>';
        document.getElementById("formulario1").addEventListener("submit", function(event) {
            event.preventDefault();
            let valid = true;

            const matricula = document.getElementById("matricula").value.trim();
            const horario = document.getElementById("horario").value.trim();

            const errorMatricula = document.getElementById("error-matricula");
            if (matricula === "") {
                errorMatricula.textContent = "Por favor, seleccione un médico.";
                errorMatricula.classList.add('show');
                valid = false;
            } else {
                errorMatricula.textContent = "";
                errorMatricula.classList.remove('show');
            }

            const errorHorario = document.getElementById("error-horario");
            if (horario === "") {
                errorHorario.textContent = "Por favor, seleccione un horario.";
                errorHorario.classList.add('show');
                valid = false;
            } else {
                errorHorario.textContent = "";
                errorHorario.classList.remove('show');
            }

            if (valid) {
                const matriculaSelect = document.getElementById("matricula");
                const selectedMatriculaText = matriculaSelect.options[matriculaSelect.selectedIndex].text;
                const horarioSelect = document.getElementById("horario");
                const selectedHorarioText = horarioSelect.options[horarioSelect.selectedIndex].text;
                const lugarReposo = <?= json_encode($caso['lugarReposo']) ?>;
                const fecha = <?= json_encode($fecha) ?>;
                const dia = <?= json_encode($dia) ?>;
                const fechaObj = new Date(fecha  + "T00:00:00"); 
                const fechaFormateada = fechaObj.toLocaleDateString("es-AR");
                const mensaje = `
                    <p><strong>Médico: </strong>${selectedMatriculaText}</p>
                    <p><strong>Fecha: </strong>${fechaFormateada}</p>
                    <p><strong>Día: </strong>${dia}</p>
                    <p><strong>Horario: </strong>${selectedHorarioText}</p>
                    <p><strong>Lugar: </strong>${lugarReposo}</p>
                `;
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

        document.getElementById("matricula").addEventListener("change", function() {
            const matricula = document.getElementById("matricula").value;

            if (matricula) {
                const errorMatricula = document.getElementById("error-matricula");
                errorMatricula.textContent = "";
                errorMatricula.classList.remove('show');
                fetch(`${baseUrl}mostrarHorario/${matricula}`)
                    .then(response => response.json())
                    .then(data => {
                        const horarioSelect = document.getElementById("horario");
                        horarioSelect.innerHTML = '<option value="">Seleccione un Horario</option>';
                        horarioSelect.disabled = false;

                        if (data.success) {
                            data.horarios.forEach(horario => {
                                const option = document.createElement("option");
                                option.value = horario.id;
                                option.textContent = `${horario.horaInicio} - ${horario.horaFin}`;
                                horarioSelect.appendChild(option);
                            });
                        } else if (data.error) {
                            horarioSelect.innerHTML = '<option value="">' + data.error + '</option>';
                            horarioSelect.disabled = true;
                        } else if (data.trabaja) {
                            horarioSelect.innerHTML = '<option value="">' + data.trabaja + '</option>';
                            horarioSelect.disabled = true;
                        }
                    })
                    .catch(() => {
                        const horarioSelect = document.getElementById("horario");
                        horarioSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
                        horarioSelect.disabled = true;
                    });
            } else {
                const horarioSelect = document.getElementById("horario");
                horarioSelect.innerHTML = '<option value="">Seleccione un Horario</option>';
                horarioSelect.disabled = true;
            }
        });
    </script>
</body>

</html>