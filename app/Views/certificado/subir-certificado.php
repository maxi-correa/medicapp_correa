<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Subir Certificado</title>

    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css'); ?>">

    <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/layout.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/navbar.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/buttons.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/colores.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/alerts.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/divs.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/form.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/tables.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/texto.css">
    <style>
        /* Estilos para ocultar las flechas */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .info-message {
            padding: 5px;
            border-radius: 8px;
            font-size: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            background: gray;
            width: auto;
            color: white;
        }
    </style>
</head>

<body>
    <header class="header" style="margin-bottom: 2%;">
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
            <div class="form-container" style="border: none;">
                <h4 class="card-title texto-blanco azul borde mb-2">Subir Certificado</h4>
                
                <form id="formulario1" action="<?= base_url('certificado') ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fechaEmision">Fecha de emision del certificado:</label>
                        <input type="date" id="fechaEmision" name="fechaEmision"
                            min="<?php echo date('Y-m-d'); ?>"
                            max="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
                        <span class="error-message" id="error-fechaEmision"></span>
                    </div>
                    <div class="form-group">
                        <label for="enfermedad">Enfermedad:</label>
                        <select name="enfermedad" id="enfermedad">
                            <option value="">-- Selecciona --</option>
                            <?php foreach ($enfermedades as $enfermedad): ?>
                                <option value="<?php echo $enfermedad['codEnfermedad']; ?>">
                                    <?php echo $enfermedad['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error-message" id="error-enfermedad"></span>
                        <?php if (session()->getFlashdata('mensaje_tipo') == 'enfermedad'): ?>
                            <div class="alert medico">
                                <?= session()->getFlashdata('mensaje') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="reposo">Reposo:</label>
                        <input type="number" id="reposo" name="reposo" min="1" max="31" placeholder="1 - 31 días">
                        <span class="d-flex  info-message" id="enfermedad-dias"></span>
                        <span class="error-message" id="error-reposo"></span>
                    </div>
                    <div class="form-group">
                        <label for="matricula">Matricula:</label>
                        <input type="number" id="matricula" name="matricula" placeholder="10000">
                        <span class="d-flex info-message" id="medico-info"></span>
                        <span class="error-message" id="error-matricula"></span>

                    </div>
                    <div class="form-group">
                        <label for="direccion">Lugar de reposo:</label>
                        <input type="text" id="direccion" name="direccion" maxlength="220" placeholder="Barrio Calle N°">
                        <span class="error-message" id="error-direccion"></span>
                    </div>
                    <div class="form-group">
                        <label for="certificado">Certificado</label>
                        <input type="file" id="certificado" name="certificado" accept=".jpg, .png">
                        <span class="error-message" id="error-certificado"></span>
                    </div>
                    <div class="contenido-centrado">
                        <span id="error-formulario"></span>
                        <button type="submit" class="verde">Confirmar</button>
                        <a href="<?= base_url('visualizarCasoE') ?>" class="rojo">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Modal de confirmación -->
        <div id="modal" class="modal">
            <div class="modal-content mt-2">
                <span class="close" id="closeModal">&times;</span>
                <h2 class="borde texto-blanco azul">Resumen de Información</h2>
                <div>
                    <p id="modal-mensaje" class=" borde"></p>
                </div>
                <div class="contenido-centrado"><button class="verde" id="confirmarEnvio">Confirmar Envío</button></div>
            </div>
        </div>

    </main>
    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 medicapp. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script>
        const baseUrl = '<?= base_url() ?>';


        const enfermedadInput = document.getElementById("enfermedad");
        const reposoInput = document.getElementById("reposo");

        async function verificarDias(enfermedad, reposo) {
            try {
                const response = await fetch(`${baseUrl}verificardias/${enfermedad}/${reposo}`);
                const data = await response.json();

                if (data.error) {
                    document.getElementById('enfermedad-dias').textContent = data.error;
                    document.getElementById('enfermedad-dias').classList.add('error');
                } else {
                    document.getElementById('enfermedad-dias').textContent = data.confirmar;
                    document.getElementById('enfermedad-dias').classList.remove('error');
                }
            } catch (error) {
                console.error("Error al verificar días:", error);
            }
        }

        enfermedadInput.addEventListener("input", () => {
            const enfermedad = enfermedadInput.value;
            console.log(enfermedad);
            const reposo = reposoInput.value;
            verificarDias(enfermedad, reposo);
        });

        reposoInput.addEventListener("input", () => {
            const enfermedad = enfermedadInput.value;
            const reposo = reposoInput.value;
            verificarDias(enfermedad, reposo);
        });

        document.getElementById("reposo").addEventListener("input", function() {
            const reposoInput = this.value.trim();
            const errorElement = document.getElementById("error-reposo");
            errorElement.textContent = "";

            if (reposoInput === "") {
                errorElement.textContent = "Por favor, ingrese los días de reposo.";
                errorElement.classList.add('show');
            } else {
                const value = parseInt(reposoInput, 10);
                if (isNaN(value) || value < 1 || value > 31) {
                    errorElement.textContent = "El reposo debe ser entre 1 y 31 días.";
                    errorElement.classList.add('show');
                } else {
                    errorElement.textContent = "";
                    errorElement.classList.remove('show');
                }
            }
        });

        document.getElementById("matricula").addEventListener("input", function() {
            const matriculaInput = this.value.trim();
            const errorElement = document.getElementById('error-matricula');
            const medicoInfoElement = document.getElementById('medico-info');
            medicoInfoElement.textContent = "";
            errorElement.textContent = "";

            if (matriculaInput === "") {
                return;
            } else {
                fetch(`${baseUrl}buscarMedico/${matriculaInput}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            medicoInfoElement.textContent = data.error;
                            medicoInfoElement.classList.add('error');
                        } else if (data.nombreCompleto) {
                            medicoInfoElement.textContent = `Médico: ${data.nombreCompleto}`;
                            medicoInfoElement.classList.remove('error');
                        }
                    })
                    .catch(() => {
                        medicoInfoElement.textContent = 'Error al verificar el médico.';
                        medicoInfoElement.classList.add('error');
                    });
            }

        });

        document.getElementById("formulario1").addEventListener("submit", function(event) {
            event.preventDefault();

            ["fechaEmision", "enfermedad", "reposo", "matricula", "direccion"].forEach(id => {
                document.getElementById(id).removeAttribute('required');
            });

            let valid = true;

            const errorElements = [
                "error-fechaEmision", "error-enfermedad",
                "error-reposo", "error-matricula",
                "error-direccion", "error-certificado"
            ];

            errorElements.forEach(id => {
                const element = document.getElementById(id);
                element.textContent = "";
                element.classList.remove('show');
            });

            const fechaEmision = document.getElementById("fechaEmision").value.trim();
            if (fechaEmision === "") {
                const errorElement = document.getElementById("error-fechaEmision");
                errorElement.textContent = "Por favor, ingrese la fecha de emisión del certificado.";
                errorElement.classList.add('show');
                valid = false;
            }

            const enfermedad = document.getElementById("enfermedad").value.trim();
            if (enfermedad === "") {
                const errorElement = document.getElementById("error-enfermedad");
                errorElement.textContent = "Por favor, selecciona una enfermedad.";
                errorElement.classList.add('show');
                valid = false;
            }

            const reposo = document.getElementById("reposo").value.trim();
            if (reposo === "") {
                const errorElement = document.getElementById("error-reposo");
                errorElement.textContent = "Por favor, ingrese los días de reposo.";
                errorElement.classList.add('show');
                valid = false;
            }

            const matricula = document.getElementById("matricula").value.trim();
            if (matricula === "") {
                const errorElement = document.getElementById("error-matricula");
                errorElement.textContent = "Por favor, ingrese la matrícula del médico.";
                errorElement.classList.add('show');
                valid = false;
            }

            const direccion = document.getElementById("direccion").value.trim();
            if (direccion === "") {
                const errorElement = document.getElementById("error-direccion");
                errorElement.textContent = "Por favor, ingrese la dirección del lugar de reposo.";
                errorElement.classList.add('show');
                valid = false;
            }

            const certificado = document.getElementById("certificado");
            const maxSize = 5 * 1024 * 1024;
            const validExtensions = ["image/jpg", "image/png"]; // Tipos MIME permitidos

            if (certificado.value.trim() === "") {
                const errorElement = document.getElementById("error-certificado");
                errorElement.textContent = "Por favor, selecciona un archivo.";
                errorElement.classList.add('show');
                valid = false;
            } else if (!validExtensions.includes(certificado.files[0].type)) {
                const errorElement = document.getElementById("error-certificado");
                errorElement.textContent = "Solo se permiten archivos JPG y PNG.";
                errorElement.classList.add('show');
                certificado.value = "";
                valid = false;
            } else if (certificado.files[0].size > maxSize) {
                const errorElement = document.getElementById("error-certificado");
                errorElement.textContent = "El archivo no debe superar los 5 MB.";
                errorElement.classList.add('show');
                certificado.value = "";
                valid = false;
            }

            const medicoPromise = fetch(`${baseUrl}buscarMedico/${matricula}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('medico-info').textContent = data.error;
                        document.getElementById('medico-info').classList.add('error');
                        valid = false;
                    } else if (data.nombreCompleto) {
                        document.getElementById('medico-info').textContent = `Médico: ${data.nombreCompleto}`;
                        document.getElementById('medico-info').classList.remove('error');
                    }
                });

            Promise.all([medicoPromise])
                .then(() => {
                    if (valid) {
                        const select = document.getElementById("enfermedad");
                        const selectValue =select.value;
                        const indexSeleccionado = select.selectedIndex; // Índice de la opción seleccionada
                        //console.log(selectValue);
                        //console.log(indexSeleccionado);
                        //console.log(select.val());
                        const textoSeleccionado = select.options[indexSeleccionado].text;
                        //console.log(Number(enfermedad));
                        //console.log(textoSeleccionado);
                        const fechaObj = new Date(fechaEmision  + "T00:00:00"); 
                        const fechaFormateada = fechaObj.toLocaleDateString("es-AR");
                        const mensaje = `
                        <p><strong>Fecha Emision:</strong> ${fechaFormateada}</p>
                        <p><strong>Matricula del Médico:</strong> ${matricula}</p>
                        <p><strong>Enfermedad: </strong>${textoSeleccionado}</p>
                        <p><strong>Dias de reposo: </strong>${reposo}</p>
                        <p><strong>Lugar de reposo: </strong>${direccion}</p>
                        `;
                        mostrarModal(mensaje);
                    }
                })
                .catch(() => {
                    document.getElementById('error-formulario').textContent = 'Error al verificar la información.';
                    document.getElementById('error-formulario').classList.add('error');
                });
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
</body>

</html>