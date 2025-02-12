<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Registrar Caso</title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css');?>">
      <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css');?>">
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
    <style>
        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            padding: 8px;
            border: 1px solid #dc3545;
            border-radius: 5px;
            display: none;
            font-size: 14px;
        }

        .error-message.show {
            display:block;
        }
    </style>
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
                <h4 class="card-title texto-blanco azul borde mb-2">Registrar Caso</h4>
                <div class="celeste border rounded-3 p-2 pb-0">
                    <p>Por favor, completa los datos necesarios para registrar tu caso de ausencia.</p>
                </div>
                <form id="formulario1" method="POST" action="<?= base_url('registrar-caso') ?>">
                    <div class="form-group mt-2">
                        <label for="fechaAusencia">Fecha de Ausencia: </label>
                        <input type="date"
                            id="fechaAusencia"
                            name="fechaAusencia"
                            min="<?php echo date('Y-m-d'); ?>"
                            max="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
                        <span class="mt-2 error-message" id="error-fecha"></span>
                    </div>
                    <div class="form-group">
                        <label for="corresponde">Corresponde:</label>
                        <select name="corresponde" id="corresponde" onchange="toggleFamiliares()">
                            <option value="">Seleccione a quien corresponde</option>
                            <option value="Propio">Propio</option>
                            <option value="Familiar">Familiar</option>
                        </select>
                        <span class="mt-2 error-message"  id="error-corresponde"></span>
                    </div>
                    <div class="card mt-1 my-3" id="familiars-container" style="display:none;">
                        <div class="card-header celeste texto-blanco borde">
                            <label class="mb-0">Seleccione un Familiar</label>
                        </div>
                        <div class="card-body mb-1">
                            <?php if (!empty($familiares)): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($familiares as $familiar): ?>
                                        <li class="list-group-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" id="<?= esc($familiar['idFamiliar']) ?>" name="familiar" value="<?= esc($familiar['idFamiliar']) ?>">
                                                <label class="form-check-label" for="<?= esc($familiar['idFamiliar']) ?>">
                                                    <strong><?= esc($familiar['relacion']) ?></strong> <br><?= esc($familiar['apellido']) ?> <?= esc($familiar['nombre']) ?>
                                                </label>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-center texto-rojo">No hay familiares registrados</p>
                            <?php endif; ?>
                        </div>
                        <span class="mt-2 error-message"  id="error-familiar"></span>
                    </div>
                    <div class="form-group">
                        <label for="motivo">Motivo de Ausencia:</label>
                        <textarea name="motivo"
                            id="motivo"
                            rows="2"
                            maxlength="50"
                            placeholder="Indica el motivo de tu ausencia."
                            oninput="actualizarContador()"></textarea>
                        <span class="error-message"  id="error-motivo"></span>
                        <p id="contador">50 caracteres restantes</p>
                    </div>
                    <div class="contenido-centrado">
                        <button id="btnEnviar" class="verde" type="submit">Confirmar</button>
                        <a class="rojo" href="<?= base_url('menu-empleado') ?>">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de confirmación -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <h2 class="texto-verde fw-bold">¡Registro Exitoso!</h2>
                <p><strong>Caso pendiente.</strong></p>
                <p><strong>Número de Trámite:</strong> <span id="numeroTramite"></span></p>
                <div class="contenido-columna">
                    <p>Tu caso de ausencia ha sido registrado correctamente.</p>
                    <div class="contenido-centrado mt-2 mb-2">
                        <a class="verde" href="<?= base_url('certificado') ?>">Subir Certificado</a>
                        <a class="gris texto-azul" href="<?= base_url('menu-empleado') ?>">Omitir</a>
                    </div>
                    <p><i class="fas fa-bell"></i>Tienes un plazo de 72 horas para subir el certificado.</p>
                </div>
            </div>
        </div>

    </main>
    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 medicapp_dev. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js');?>"></script>
    <script>
        function toggleFamiliares() {
            const correspondeSelect = document.getElementById("corresponde");
            const familiarsContainer = document.getElementById("familiars-container");

            if (correspondeSelect.value === "Familiar") {
                familiarsContainer.style.display = "block";
            } else {
                familiarsContainer.style.display = "none";
            }
        }

        function actualizarContador() {
            const maxLength = 50;
            const motivo = document.getElementById("motivo");
            const contador = document.getElementById("contador");
            const caracteresRestantes = maxLength - motivo.value.length;
            contador.textContent = `${caracteresRestantes} caracteres restantes`;
        }

        document.getElementById("formulario1").addEventListener("submit", function(event) {
            event.preventDefault();
            let valid = true;

            const fechaAusencia = document.getElementById("fechaAusencia").value;
            if (!fechaAusencia) {
                document.getElementById("error-fecha").textContent = "Por favor, selecciona una fecha de ausencia.";
                document.getElementById("error-fecha").classList.add('show');
                valid = false;
            } else {
                document.getElementById("error-fecha").textContent = "";
                document.getElementById("error-fecha").classList.remove('show');
            }

            const corresponde = document.getElementById("corresponde").value;
            if (!corresponde) {
                document.getElementById("error-corresponde").textContent = "Por favor, selecciona una opción en 'Corresponde'.";
                document.getElementById("error-corresponde").classList.add('show');
                valid = false;
            } else {
                document.getElementById("error-corresponde").textContent = "";
                document.getElementById("error-corresponde").classList.remove('show');
            }

            const familiarChecked = document.querySelector('input[name="familiar"]:checked');
            if (corresponde === "Familiar" && !familiarChecked) {
                document.getElementById("error-familiar").textContent = "Por favor, selecciona un familiar.";
                document.getElementById("error-familiar").classList.add('show');
                valid = false;
            } else {
                document.getElementById("error-familiar").textContent = "";
                document.getElementById("error-familiar").classList.remove('show');
            }

            const motivo = document.getElementById("motivo").value.trim();
            if (!motivo) {
                document.getElementById("error-motivo").textContent = "Por favor, indica el motivo de tu ausencia.";
                document.getElementById("error-motivo").classList.add('show');
                valid = false;
            } else {
                document.getElementById("error-motivo").textContent = "";
                document.getElementById("error-motivo").classList.remove('show');
            }

            if (valid) {
                const btnEnviar = document.getElementById("btnEnviar");
                btnEnviar.disabled = true;
                btnEnviar.textContent = "Enviando...";
                fetch('<?= base_url('registrar-caso') ?>', {
                        method: 'POST',
                        body: new FormData(document.getElementById("formulario1"))
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Respuesta del servidor:', data);
                        if (data.success) {
                            const numeroTramite = data.numeroTramite;
                            mostrarModal(numeroTramite);
                        } else {
                            alert("Hubo un error al registrar el caso.");
                        }
                    })
                    .catch(error => {
                        console.error('Error al registrar el caso:', error);
                    })

                    .finally(() => {
                        btnEnviar.disabled = false;
                        btnEnviar.textContent = "Registrar Caso";
                    });
            }
        });

        function mostrarModal(numeroTramite) {
            document.getElementById("numeroTramite").textContent = numeroTramite;
            const modal = document.getElementById("modal");
            modal.style.display = "block";
        }

        document.getElementById("confirmarEnvio").onclick = function() {};
    </script>
</body>

</html>


