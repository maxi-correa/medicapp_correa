<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Cambiar Contraseña</title>
    
    <?= view('templates/link'); ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        a.disabled {
            pointer-events: none;
            background: var(--gray);
            text-decoration: none;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <header class="header">
        <?= view('templates/menu'); ?>
    </header>

    <!--###################################################################################################################################-->
    <main class="content">



<div class="contenido-centrado">

  <div class="form-container">
      <h4 class="card-title texto-blanco azul borde mb-2">Modificar Contraseña</h4>
      <div class="celeste border rounded-3 p-2 pb-0">
        <p>Por favor, completa los campos para efectuar la operación</p>
      </div>
        <form action="<?= base_url('perfil/actualizarContrasena') ?>" method="POST" id="changeForm">
          
          <!-- Nueva Contraseña -->
          <div class="mb-3">
            <label for="nueva_contraseña" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" autocomplete="new-password" >
          </div>

          <!-- Confirmar Contraseña -->
          <div class="mb-3">
            <label for="confirmar_contraseña" class="form-label">Confirmar Contraseña</label>
            <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" autocomplete="new-password" >
          </div>

          <!-- Mensajes de error o éxito -->
          <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
          <?php endif; ?>
          
          <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
          <?php endif; ?>

          <!-- Botón para enviar el formulario -->
          <div class="contenido-centrado" style="margin-bottom: 5%;">
            <button id="guardarCambios" class="verde" type="submit">Confirmar</button>
            <a class="rojo" href="<?= base_url('perfil') ?>">Cancelar</a>
          </div>
        </form>
  </div>
</div>
    </main>

    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <?= view('templates/footer'); ?>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('../../../public/assets/js/tablas/perfil.js'); ?>"></script>

</body>

</html>