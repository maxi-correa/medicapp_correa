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

        /* Overlay */
    .modal-custom {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(3px);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    /* Caja modal */
    .modal-box {
        background: #ffffff;
        width: 420px;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        animation: fadeInScale 0.2s ease-out;
    }

    /* Animación suave */
    @keyframes fadeInScale {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-header {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group-modal {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }

    .form-group-modal label {
        font-size: 14px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .input-normal,
    .input-readonly {
        padding: 8px 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .input-readonly {
        background-color: #f1f1f1;
        color: #666;
        pointer-events: none;
        cursor: not-allowed;
    }

    .error-text {
        display: none;
        font-size: 13px;
        color: #d9534f;
    }

    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .close {
        font-size: 20px;
        cursor: pointer;
    }

    </style>
</head>

<body>


    <header class="header" style="margin-bottom: 5%;">
        <?= view('templates/menu'); ?>
    </header>

    <!--###################################################################################################################################-->
    <main class="content">
        <!-- Encabezado -->
        <div class="container">
            <div class="d-flex align-items-center justify-content-between encabezado ">
                <button class='rojo' onclick="location.href='<?= base_url('medicos')?>'">Atrás</button>
                <div class="encabezado-blanco text-center texto-negro raleway fw-bold display-6 flex-grow-1">HABILITAR/DESHABILITAR MÉDICO</div>
            </div>
        </div>

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
                                    <td><?= date('d-m-Y', strtotime($turno['fecha'])); ?></td>
                                    <td><?= date('H:i', strtotime($turno['hora'])); ?></td>
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
                            <label for="">Confirmar Deshabilitación: </label>
                        </div>
                        <div>
                            <a class="rojo me-1" href="javascript:void(0);" id="btna" data-matricula="<?= $medico['matricula'] ?>" data-url="<?= base_url('medicos/deshabilitarMedico') ?>/<?= $medico['matricula'] ?>">Deshabilitar permanentemente</a>
                            <a class="rojo me-1" href="javascript:void(0);" id="btnt">Deshabilitar temporalmente</a>
                        </div>
                    </div>
                </div>

                <div id="deshabilitarContenedor" class="deshabilitarContenedor">
                    <label for="">Confirmar habilitación: </label>
                    <a class="verde me-1" href="#" id="btnHabilitar" data-matricula="<?= $medico['matricula'] ?>" data-url="<?= base_url('medicos/habilitarMedico') ?>/<?= $medico['matricula'] ?>">Habilitar</a>
                </div>
            </div>

        </div>

        <!-- Modal Confirmar Deshabilitación Permanente -->
        <div class="modal fade" id="modalConfirmarDeshabilitar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

            <div class="modal-header">
                <h3>Deshabilitar Médico</h3>
                <span class="close">&times;</span>
            </div>

            <div class="modal-body text-center">
                <p>¿Está seguro que desea deshabilitar permanentemente al médico?</p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="rojo" data-bs-dismiss="modal">
                Cancelar
                </button>
                <button type="button" id="btnConfirmarDeshabilitacion" class="verde">
                    Confirmar
                </button>
            </div>

            </div>
        </div>
        </div>

        <!-- Modal deshabilitar temporalmente -->
        <div id="modalDeshabilitar" class="modal-custom">
            <div class="modal-box">
                <div class="modal-header">
                    <h3>Deshabilitar Médico Temporalmente</h3>
                    <span class="close">&times;</span>
                </div>

                <div class="modal-body">
                    <div class="form-group-modal">
                        <label>Desde</label>
                        <input type="date" id="fechaDesde" class="input-normal">
                    </div>

                    <div class="form-group-modal">
                        <label>Hasta</label>
                        <input type="date" id="fechaHasta" class="input-normal">
                    </div>

                    <p id="errorMensaje" class="error-text">
                    La fecha "Desde" no pude ser menor a la fecha actual.<br>    
                    La fecha "Hasta" debe ser mayor o igual a "Desde"
                    </p>
                </div>

                <div class="modal-footer">
                    <button id="cancelarTemporal" class="rojo">Cancelar</button>
                    <button id="confirmarTemporal" class="verde"
                        data-matricula="<?= $medico['matricula'] ?>"
                        data-url="<?= base_url('medicos/deshabilitarTurnos') ?>/<?= $medico['matricula'] ?>">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>

    </main>
    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <?= view('templates/footer'); ?>

    <script>
    document.addEventListener("DOMContentLoaded", function () {

        /* =====================================================
        1️⃣ Mostrar contenedores según estado del médico
        ===================================================== */

        const habilitado = "<?= $medico['habilitado'] ?>";

        const habilitarContenedor = document.getElementById('habilitarContenedor');
        const deshabilitarContenedor = document.getElementById('deshabilitarContenedor');

        habilitarContenedor.style.display = 'none';
        deshabilitarContenedor.style.display = 'none';

        if (habilitado == '0') {
            deshabilitarContenedor.style.display = 'block';
        } else if (habilitado == '1') {
            habilitarContenedor.style.display = 'block';
        }

        /* =====================================================
        2️⃣ Habilitar médico (AJAX)
        ===================================================== */

        const btnHabilitar = document.getElementById('btnHabilitar');

        if (btnHabilitar) {
            btnHabilitar.addEventListener('click', function (event) {
                event.preventDefault();

                const url = this.getAttribute('data-url');

                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('Error del servidor');
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert("Error: " + data.message);
                        }
                    })
                    .catch(error => {
                        alert("Error: " + error.message);
                    });
            });
        }

        /* =====================================================
        3️⃣ Modal deshabilitación temporal
        ===================================================== */

        const modalTemporal = document.getElementById("modalDeshabilitar");
        const btnTemporal = document.getElementById("btnt");
        const closeBtn = document.querySelector(".close");
        const cancelBtn = document.getElementById("cancelarTemporal");
        const confirmarBtn = document.getElementById("confirmarTemporal");
        const fechaDesdeInput = document.getElementById("fechaDesde");
        const fechaHastaInput = document.getElementById("fechaHasta");
        const errorMensaje = document.getElementById("errorMensaje");

        const hoy = new Date().toISOString().split('T')[0];
        fechaDesdeInput.value = hoy;

        btnTemporal.addEventListener("click", function () {
            modalTemporal.style.display = "flex";
        });

        closeBtn.addEventListener("click", function () {
            modalTemporal.style.display = "none";
        });

        cancelBtn.addEventListener("click", function () {
            modalTemporal.style.display = "none";
        });

        window.addEventListener("click", function (event) {
            if (event.target === modalTemporal) {
                modalTemporal.style.display = "none";
            }
        });

        confirmarBtn.addEventListener("click", function () {

            const fechaDesde = fechaDesdeInput.value;
            const fechaHasta = fechaHastaInput.value;
            const matricula = confirmarBtn.getAttribute("data-matricula");
            const url = "<?= base_url('medicos/deshabilitarMedicoTemporal') ?>";

            if (!fechaDesde || !fechaHasta || fechaHasta < fechaDesde || fechaDesde < hoy) {
                errorMensaje.style.display = "block";
                return;
            }

            errorMensaje.style.display = "none";
            modalTemporal.style.display = "none";

            fetch(url, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    matricula: matricula,
                    fechaDesde: fechaDesde,
                    fechaHasta: fechaHasta
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Médico deshabilitado correctamente");
                    location.reload();
                } else {
                    alert("Error: " + data.message);
                }
            });
        });

        /* =====================================================
        4️⃣ Modal deshabilitación permanente (Bootstrap)
        ===================================================== */

        const botonDeshabilitar = document.getElementById("btna");
        const btnConfirmar = document.getElementById("btnConfirmarDeshabilitacion");
        const modalElement = document.getElementById("modalConfirmarDeshabilitar");
        const modalBootstrap = new bootstrap.Modal(modalElement);

        let urlDeshabilitar = "";

        botonDeshabilitar.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopImmediatePropagation(); // 🔴 más fuerte que stopPropagation

            urlDeshabilitar = this.getAttribute("data-url");

            modalBootstrap.show();
        });

        btnConfirmar.addEventListener("click", function () {

            fetch(urlDeshabilitar)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        modalBootstrap.hide();
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    alert("Error: " + error.message);
                });

        });
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