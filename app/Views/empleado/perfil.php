<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Perfil</title>
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
        <div class="container mt-4">
            <div class="col">
                <div class="row-md-8">
                    <div class="card mb-4 gris">
                        <div class="card-body">
                            <div class="card-title texto-blanco text-uppercase azul font-weight-bold" style="font-size:23px;">
                                <div class="ml-4">Información del Empleado</div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="me-4 mt-5 row-cols-1 ">
                                    <i class="fas fa-user-circle fa-10x me-4 texto-azul text-center"></i>
                                    <a href="<?= base_url('perfil/historial') ?>" class="violeta m-1 <?= empty($empleado) ? 'disabled' : '' ?>" onclick="<?= empty($empleado) ? 'event.preventDefault();' : '' ?>">Historial de Casos</a>
                                    <a href="<?= base_url('perfil/modificarcontrasena') ?>" class="violeta m-1 <?= empty($empleado) ? 'disabled' : '' ?>" onclick="<?= empty($empleado) ? 'event.preventDefault();' : '' ?>">Modificar Contraseña</a>
                                    <button class='rojo m-1' onclick="location.href='<?= base_url('menu-empleado')?>'">Atrás</button>
                                </div>
                                <div class="info blanco rounded col-md-8 p-3">
                                    <?php if (!empty($empleado)): ?>
                                        <p><strong>Legajo:</strong> <?= esc($empleado['legajo']) ?></p>
                                        <p><strong>Nombre:</strong> <?= esc($empleado['nombre']) ?></p>
                                        <p><strong>Apellido:</strong> <?= esc($empleado['apellido']) ?></p>
                                        <p><strong>D.N.I:</strong> <?= esc($empleado['dni']) ?></p>
                                        <p><strong>Sector:</strong> <?= esc($empleado['sector']) ?></p>
                                        <p><strong>Rol:</strong> <?= esc($empleado['rol']) ?></p>
                                        <p><strong>Fecha Nacimiento:</strong> <?= esc(DateTime::createFromFormat("Y-m-d", $empleado['fechaNacimiento'])->format("d/m/Y")) ?></p>
                                        <p><strong>Correo Electrónico:</strong> <?= esc($empleado['gmail']) ?></p>
                                        <p><strong>Telefono:</strong> <?= esc($empleado['telefono']) ?></p>
                                    <?php else: ?>
                                        <p class="texto-rojo text-center p-3">No hay información disponible.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-md-8">
                        <div class="card mb-4 gris ">
                            <div class="card-body">
                                <div class="card-title texto-blanco azul texto-blanco text-uppercase font-weight-bold" style="font-size:23px;">
                                    <div class="ml-4">Familiares Registrados</div>
                                </div>
                                <div class="table-responsive mt-1 tabla-contenedor">
                                    <table class="table ">
                                        <thead class="azul text-uppercase">
                                            <tr>
                                                <th>Relación</th>
                                                <th>Apellido</th>
                                                <th>Nombre</th>
                                                <th>DNI</th>
                                                <th>Fecha de Nacimiento</th>
                                                <th>Gmail</th>
                                                <th>Teléfono</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($familiares)): ?>
                                                <?php foreach ($familiares as $familiar): ?>
                                                    <tr>
                                                        <td><?= esc($familiar['relacion']) ?></td>
                                                        <td><?= esc($familiar['apellido']) ?></td>
                                                        <td><?= esc($familiar['nombre']) ?></td>
                                                        <td><?= esc($familiar['dni']) ?></td>
                                                        <td><?= esc(DateTime::createFromFormat("Y-m-d", $familiar['fechaNacimiento'])->format("d/m/Y")) ?></td>
                                                        <td><?= esc($familiar['gmail']) ?></td>
                                                        <td><?= esc($familiar['telefono']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" class="texto-rojo text-center">No hay familiares registrados.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="container">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
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
    <script src="<?= base_url('assets/js/perfil.js'); ?>"></script>

</body>

</html>