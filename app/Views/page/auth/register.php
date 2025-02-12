<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <link rel="stylesheet" href="/medicapp_dev/public/assets/css/variables.css">
        <link rel="stylesheet" href="/medicapp_dev/public/assets/css/layout.css">
        <link rel="stylesheet" href="/medicapp_dev/public/assets/css/divs.css">
        <link rel="stylesheet" href="/medicapp_dev/public/assets/css/form.css">
        <link rel="stylesheet" href="/medicapp_dev/public/assets/css/colores.css">
        <link rel="stylesheet" href="/medicapp_dev/public/assets/css/buttons.css">
        <link rel="stylesheet" href="/medicapp_dev/public/assets/css/texto.css">
        <title>Medicapp - Registro</title>

        <style>
            body {
                background-image: url('public/assets/img/fondo.png');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                height: 100vh;
                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: medium;
            }

            .container {
                width: 100%;
                max-width: 600px;
                background-color: rgba(255, 255, 255, 0.1);
                padding: 20px;
                border-radius: 20px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);
                max-width: 500px;
            }

            .alert-negativa {
                padding: 10px;
                background-color: rgba(255, 0, 0, 0.5);
                color: white;
                margin-bottom: 15px;
                text-align: center;
                border-radius: 8px;
                border: 2px solid red;
            }

            .alert-cuenta {
                padding: 10px;
                background-color: rgba(70, 130, 180, 0.6);
                color: white;
                margin-bottom: 15px;
                text-align: center;
                border-radius: 8px;
                border: 2px solid #4682b4;
            }


            .error-message {
                color: #dc3545;
                background-color: #f8d7da;
                padding: 8px;
                border: 1px solid #dc3545;
                border-radius: 5px;
                display: none;
                font-size: 14px;
            }

            .show {
                display: block;
            }

            .label-icono {
                display: flex;
                justify-content: space-between;
            }

            .label-icono i {
                cursor: pointer;
                font-size: large;
            }
        </style>

    </head>
    <div class="container">
        <div class="contenido-centrado">
            <h1 class="texto-blanco fw-bold">Registro</h1>
        </div>
        <form id="miFormulario" action="<?= base_url('registro') ?>" method="POST">
            <?php if (session()->getFlashdata('mensaje')): ?>
                <?php
                $mensajeTipo = session()->getFlashdata('mensaje_tipo') ?? 'info';
                $alertClass = ($mensajeTipo === 'error') ? 'alert-negativa' : 'alert-cuenta';
                ?>
                <div class="<?= $alertClass ?>">
                    <?= session()->getFlashdata('mensaje') ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label class="texto-blanco" sesión aquí for="legajo">Legajo</label>
                <input type="number" name="legajo" id="legajo" step="1" min="0" max='10000'>
                <span class="error-message" id="error-legajo"></span>
            </div>
            <div class="form-group">
                <label class="texto-blanco" for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email">
                <span class="error-message" id="error-email"></span>
            </div>
            <div class="form-group">
                <div class="label-icono">
                    <label class="texto-blanco" for="contrasenia">Contraseña</label>
                    <i class="fas fa-eye texto-blanco fs-5" onclick="togglePassword()"></i>
                </div>
                <input type="password" name="contrasenia" id="contrasenia">
                <span class="error-message" id="error-contrasena"></span>
            </div>
            <div class="form-group">
                <label class="texto-blanco" for="repetir_contrasenia">Repetir Contraseña</label>
                <input type="password" name="repetir_contrasenia" id="repetir_contrasenia">
                <span class="error-message" id="error-repetir-contrasena"></span>
            </div>
            <div class="contenido-centrado">
                <button type="submit" class="violeta">Registrarse</button>
            </div>
            <div class="contenido-centrado mt-4">
                <p class="texto-blanco">¿Ya tienes cuenta?<a class="border texto-violeta fw-bold" href="<?= base_url() ?>">Inicia sesión aquí</a></p>
            </div>
        </form>
    </div>
    <script>
        document.getElementById("miFormulario").addEventListener("submit", function(event) {
            event.preventDefault();

            let valid = true;

            const campos = [{
                    id: "legajo",
                    errorId: "error-legajo",
                    regex: /^\d+$/,
                    errorMsg: "Por favor, ingrese un legajo válido (solo números)."
                },
                {
                    id: "email",
                    errorId: "error-email",
                    regex: /\S+@\S+\.\S+/,
                    errorMsg: "El correo electrónico no es válido. Ejemplo: ejemplo@gmail.com"
                },
                {
                    id: "contrasenia",
                    errorId: "error-contrasena",
                    regex: /^(?=.*[A-Z])(?=.*\d).{8,}$/,
                    errorMsg: "La contraseña debe tener al menos 8 caracteres, contener al menos una letra mayúscula y un numero."
                },
            ];

            campos.forEach(campo => {
                const input = document.getElementById(campo.id);
                const errorElement = document.getElementById(campo.errorId);

                input.addEventListener("input", () => validarCampo(input, campo.regex, errorElement, campo.errorMsg));
                input.addEventListener("blur", () => validarCampo(input, campo.regex, errorElement, campo.errorMsg));

                if (!validarCampo(input, campo.regex, errorElement, campo.errorMsg)) {
                    valid = false;
                }
            });

            const contrasena = document.getElementById("contrasenia");
            const repetirContrasena = document.getElementById("repetir_contrasenia");
            const errorRepetir = document.getElementById("error-repetir-contrasena");

            repetirContrasena.addEventListener("input", () => validarCoincidencia(contrasena, repetirContrasena, errorRepetir));
            repetirContrasena.addEventListener("blur", () => validarCoincidencia(contrasena, repetirContrasena, errorRepetir));

            if (!validarCoincidencia(contrasena, repetirContrasena, errorRepetir)) {
                valid = false;
            }

            if (valid) {
                event.target.submit();
            }
        });

        function validarCampo(input, regex, errorElement, errorMsg) {
            if (!regex.test(input.value.trim())) {
                errorElement.textContent = errorMsg;
                errorElement.classList.add('show');
                input.classList.add('invalid');
                return false;
            } else {
                errorElement.textContent = "";
                errorElement.classList.remove('show');
                input.classList.remove('invalid');
                input.classList.add('valid');
                return true;
            }
        }

        function validarCoincidencia(input1, input2, errorElement) {
            if (input1.value.trim() !== input2.value.trim()) {
                errorElement.textContent = "Las contraseñas no coinciden.";
                errorElement.classList.add('show');
                return false;
            } else {
                errorElement.textContent = "";
                errorElement.classList.remove('show');
                return true;
            }
        }

        function togglePassword() {
            const passwordField = document.getElementById("contrasenia");
            const toggleIcon = document.querySelector(".label-icono i");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
    </body>

</html>