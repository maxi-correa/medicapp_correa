<!DOCTYPE html>
<html lang="es">

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
    <title>Medicapp - Inicio de Sesión</title>

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

        .alert-positiva {
            padding: 10px;
            background-color: rgba(128, 168, 40, 0.5);
            color: white;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 8px;
            border: 2px solid #80a828;
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

<body>
    <div class="container">
        <?php if (session()->getFlashdata('mensaje_tipo') == 'success'): ?>
            <div class="alert-positiva success">
                <?= session()->getFlashdata('mensaje') ?>
            </div>
        <?php endif; ?>
        <div class="contenido-centrado p-3">
            <h1 class="texto-blanco fw-bold">Inicio de Sesión</h1>
        </div>

        <form id="miFormulario" action="<?= base_url('/login') ?>" method="POST">
            <div class="form-group">
                <label class="texto-blanco" for="inputLegajoMatricula">Legajo o Matrícula</label>
                <input type="text" name="input" id="inputLegajoMatricula">
                <span class="error-message" id="error-legajo"></span>
            </div>
            <div class="form-group">
                <div class="label-icono">
                    <label class="texto-blanco" for="inputpassword">Contraseña</label>
                    <i class="fas fa-eye texto-blanco fs-5" onclick="togglePassword()"></i>
                </div>
                <input type="password" name="contrasenia" id="inputpassword">
                <span class="error-message" id="error-contrasena"></span>
            </div>
            <?php if (session()->getFlashdata('mensaje_tipo') == 'error'): ?>
                <div class="alert-negativa text-center">
                    <?= session()->getFlashdata('mensaje') ?>
                </div>
            <?php endif; ?>
            <div class="contenido-centrado">
                <button type="submit" class="violeta">Iniciar Sesión</button>
            </div>
            <div class="contenido-centrado mt-4">
                <p class="texto-blanco">¿No tienes cuenta?<a class="border texto-violeta fw-bold" href="<?= base_url('registro') ?>">Regístrate aquí</a></p>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("miFormulario").addEventListener("submit", function(event) {
            document.getElementById("inputLegajoMatricula").removeAttribute('required');
            document.getElementById("inputpassword").removeAttribute('required');

            let valid = true;

            document.getElementById("error-legajo").textContent = "";
            document.getElementById("error-contrasena").textContent = "";
            document.getElementById("error-legajo").classList.remove('show');
            document.getElementById("error-contrasena").classList.remove('show');

            const legajo = document.getElementById("inputLegajoMatricula");
            if (legajo.value.trim() === "") {
                document.getElementById("error-legajo").textContent = "Por favor, introduce tu legajo o matrícula.";
                document.getElementById("error-legajo").classList.add('show');
                valid = false;
            }
            const contrasena = document.getElementById("inputpassword");
            if (contrasena.value.trim() === "") {
                document.getElementById("error-contrasena").textContent = "Por favor, introduce tu contraseña.";
                document.getElementById("error-contrasena").classList.add('show');
                valid = false;
            }
            if (!valid) {
                event.preventDefault();
            }
        });


        function togglePassword() {
            const passwordField = document.getElementById("inputpassword");
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