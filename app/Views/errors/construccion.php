<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Falta Implementar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #254b5e ;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
        }

        .title {
            color: #e74c3c;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .message {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .back-link {
            font-size: 1rem;
            color: white;
            text-decoration: none;
            background: #254b5e;
            padding: 10px;
            border-radius: 8px;
        }

        .back-link:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="title">¡Funcionalidad en Desarrollo!</h1>
        <p class="message">Esta sección aún está en construcción. Estamos trabajando para implementarla pronto.</p>
        <img id="imagen" src="<?= base_url('assets/img/construccion.png') ?>" alt="Imagen" width="450" class="img-fluid mb-3 borde">
        <div><a  href="javascript:history.back()"  class="back-link">Volver</a></div>
    </div>
</body>

</html>