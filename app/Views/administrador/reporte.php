<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicapp - Certificados Emitidos</title>
    

    <!-- Libreria chart.js -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="<?= base_url('assets/js/chart/chart-4.4.6.js') ?>"></script>

    <!-- Enlace a Bootstrap CSS -->
     <!-- Boostrap 5 -->
     <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css');?>" >
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css');?>" >


    <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/layout.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/buttons.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/colores.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/divs.css">
    <link rel="stylesheet" href="/medicapp_correa/public/assets/css/texto.css">
    <style>
        .chart-container {
            position: relative;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            background-color: #fff;
        }

        canvas {
            max-height: 400px;
        }

        .chart-legend {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
            font-size: 0.9em;
            color: #333;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .toggle-view {
            cursor: pointer;
        }

        .dropdown-list {
            max-height: 0;
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            transition: max-height 0.5s ease, padding 0.5s ease;
        }

        .dropdown-item {
            opacity: 0;
            transition: opacity 0.5s ease;
            font-size: 14px;
            color: #333;
            padding: 5px;
        }

        .dropdown-list.show {
            max-height: 500px;
            padding: 10px;
        }

        .dropdown-item.show {
            opacity: 1;
        }
    </style>
</head>

<body>
    <header class="header" style="margin-bottom: 5%;">
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
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 mb-2 text-center rounded-top azul">
                <div class="texto-blanco  text-uppercase relaway font-weight-bold" style="font-size: 28px;">Reporte de Médicos Tratantes</div>
            </div>

            <div class="col-12 col-md-10 col-lg-8 gris">
                <div class="chart-container mt-2">
                    <canvas id="grafico"></canvas>
                    <div class="chart-legend" id="legend"></div>
                    <?php if (!empty($otrosMedicos)): ?>
                        <div class="mt-2" id="otros-medicos">
                            <span class="texto-azul borde fw-bold gris" onclick="toggleDropdown()">Ver Otros Médicos <i id="icon" class="fas fa-chevron-down"></i></span>
                            <div id="dropdown-list" class="dropdown-list">
                                <?php foreach ($otrosMedicos as $index => $medico): ?>
                                    <div class="dropdown-item" data-index="<?= $index ?>">
                                        <span class="medico-nombre"><?= $medico['nombre']; ?></span>
                                        <span class="medico-certificados">Certificados: <?= $medico['cantidad']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <!--###################################################################################################################################-->
    <!-- Pie de página -->
    <footer class="footer">
        <p>&copy; 2024 medicapp_correa. Todos los derechos reservados.</p>
    </footer>

    <!-- jQuery primero, después Popper.js, luego Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js');?>"></script>
    <script>
        function toggleDropdown() {
            const dropdownList = document.getElementById('dropdown-list');
            const items = dropdownList.querySelectorAll('.dropdown-item');

            if (!dropdownList.classList.contains('show')) {
                dropdownList.classList.add('show');
                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.add('show');
                    }, index * 100);
                });
            } else {
                items.forEach((item) => item.classList.remove('show'));
                setTimeout(() => dropdownList.classList.remove('show'), 500);
            }
        }

        const medicos = <?= json_encode($nombres) ?>;
        const cantidades = <?= json_encode($cantidades) ?>;
        const ctx = document.getElementById('grafico').getContext('2d');
        const myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: medicos,
                datasets: [{
                    label: 'Cantidad de Certificados',
                    data: cantidades,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Cantidad de Certificados por Médico',
                        font: {
                            size: 16
                        }
                    }
                }
            }
        });

        const legendContainer = document.getElementById('legend');
        myPieChart.data.datasets[0].backgroundColor.forEach((color, index) => {
            if (index < medicos.length) {
                const legendItem = document.createElement('div');
                legendItem.classList.add('legend-item');
                legendItem.innerHTML = `
                <div class="legend-color" style="background-color: ${color};"></div>
                ${medicos[index]}: ${cantidades[index]}`;
                legendContainer.appendChild(legendItem);
            }
        });
    </script>
</body>

</html>