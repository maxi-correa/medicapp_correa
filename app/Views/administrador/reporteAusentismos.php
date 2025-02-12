<!-- app/Views/reportes.php -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medicapp - Reportes</title>

  <!-- Acá se cargan los archivos CSS -->
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css');?>">
      <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css');?>">
    <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Enlaces a los archivos CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app-style-tabla.css') ?>">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/variables.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/layout.css">
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/navbar.css"> 
    <!-- <link rel="stylesheet" href="/medicapp_dev/public/assets/css/buttons.css"> -->
    <link rel="stylesheet" href="/medicapp_dev/public/assets/css/colores.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/texto.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dataTable.css');?>">
    
</head>

<body>
  <!-- Contenido de la vista -->
  <header class="header margin-10" style="margin-bottom: 5%;">
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
            <a class="nav-link" href="<?= base_url('salir')?>"><i class="fas fa-sign-out-alt"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!--###################################################################################################################################-->
  <main>
    <div class="container">
      <div>
        <div class="azul texto-blanco font-weight-bold relaway" style="font-size: 28px;">
            <div class="w-100 ml-3">REPORTE DE AUSENTISMOS DE EMPLEADOS</div>
        </div>
        <div>
          <form action="<?= base_url('reportes/exportar') ?>" method="POST">

            <div class="w-75 p-3" style="margin-left: 5%;">

              <div class="d-flex w-100">
                
                <div class="form-group row w-50"> 
                  <label class="col-sm-2 col-form-label col-form-label-sm">Desde:</label> 
                  <div class="col-sm-10">
                    <input type="date" class="form-control form-control-sm" value="<?php echo $fechaHoy; ?>" id="fechaDesde" name="fechaDesde" style="width: 20em;">
                  </div>
                </div>

                <div class="form-group row w-50"> 
                  <label class="col-sm-3 col-form-label col-form-label-sm" >Dirigido a:</label> 
                  <div class="col-sm-8">
                    <input type="text" class="form-control form-control-sm" id="dirigido" name="dirigido" style="width: 20em;">
                  </div>
                </div>

              </div>

              <div class="w-50">
                <div class="form-group row"> 
                  <label class="col-sm-2 col-form-label col-form-label-sm">Hasta:</label> 
                  <div class="col-sm-10">
                    <input type="date" class="form-control form-control-sm" value="<?php echo $fechaMesDespues; ?>" id="fechaHasta" name="fechaHasta"  style="width: 19.7em;">
                  </div>
                </div>
              </div>

          </div>
            <div class="w-100 gris text-end pr-5">
              <button type="input" class="btn verde texto-blanco" id="btnExportar"> EXPORTAR</button>
              <img src="<?= base_url('assets/img/excel.png');?>" alt="icono de excel" style="width: 32px;">
            </div>
          </form>
        </div>

        <div class="table-responsive">
        <table id="tableReporteAusentismo" class="table table-hover w-100">
          <thead class="celeste">
            <tr>
              <th>LEGAJO</th>
              <th>SECTOR</th>
              <th>NOMBRE</th>
              <th>APELLIDO</th>
              <th>CANTIDAD DE AUSENCIAS</th>
            </tr>
          </thead>
          <tbody>
            
          </tbody>
        </table>
      </div>
    </div>
      </div>
      
  </main>

  <!-- Pie de página -->
  <footer class="footer p-3 mt-auto">
    <p>&copy; 2024 medicapp_dev. Todos los derechos reservados.</p>
  </footer>
 
</body>

    

    <script src="<?= base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?= base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.js');?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.min.js');?>"></script>
    
    <script src="<?= base_url('assets/js/tablas/listarReportesAusentismos.js');?>"></script>
    
    <!-- DATATABLE -->
    <script src="<?= base_url('assets/js/dataTable/dataTable-2.1.8.js');?>"></script>
    <!-- DATATABLE rowGroup -->
    <script src="<?= base_url('assets/js/dataTable/dataTables-rowGroup1.5.0.js');?>"></script>

    <script src="<?= base_url('assets/js/tablas/botonDesplegar.js');?>"></script>
    <script src="<?= base_url('assets/js/moment/moment-2.30.1.js');?>"></script>
    
    <script>
      var reporteAusentismo = <?php echo json_encode($reporteAusentismo); ?>;
      var base_url = "<?php echo base_url(); ?>";
    </script>
    
</html>