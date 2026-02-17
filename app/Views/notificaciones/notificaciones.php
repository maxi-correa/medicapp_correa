<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Notificaciones</title>
    
    <?= view('templates/link'); ?>
    
    <style>
        .encabezado {
            display: flex;
            align-items: center;
        }

        .encabezado a {
            margin-right: auto;
        }

        .encabezado .encabezado-blanco {
            flex-grow: 1;
            text-align: center;
        }
    </style>
</head>

<body>
    <header class="header" style="margin-bottom: 5%;">
        <?= view('templates/menu'); ?>
    </header>
    <main style="margin: 20px;">
        <div class="container">
            <div class="relaway text-center">
                <h1>NOTIFICACIONES</h1>
            </div>
        </div>
        
        <div style="text-align: left;">
            <button class="rojo" onclick="history.back()">Volver</button>
            <br><br>
        </div>
        
        <?php if (empty($notificaciones)): ?>
            <div class="alert alert-info">No tiene notificaciones.</div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($notificaciones as $n): ?>
                    <li class="list-group-item">
                        <strong><?= esc($n['tipo']) ?></strong><br>
                        <?= esc($n['mensaje']) ?><br>
                        <?php if (!empty($n['link'])): ?>
                            <div>
                                <button class="verde" onclick="location.href='<?= $n['link'] ?>'">Ver</button>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($totalPaginas > 1): ?>
            <nav class="mt-4 d-flex justify-content-center">
                <ul class="pagination">

                    <!-- Botón Anterior -->
                    <li class="page-item <?= ($paginaActual <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="?page=<?= $paginaActual - 1 ?>">
                            ◀ Anterior
                        </a>
                    </li>

                    <!-- Botón Siguiente -->
                    <li class="page-item <?= ($paginaActual >= $totalPaginas) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="?page=<?= $paginaActual + 1 ?>">
                            Siguiente ▶
                        </a>
                    </li>

                </ul>
            </nav>
        <?php endif; ?>
    </main>

    <?= view('templates/footer'); ?>

</body>

<!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/popper.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

</html>