<!DOCTYPE html>
<html lang="es" >
<head>
  <meta charset="UTF-8">
  <title>Turnos</title>
  <link rel="stylesheet" href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
  
    <link rel="stylesheet" href="<?= base_url('assets/css/sb-admin-2.min.css');?>" >
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css');?>" >

</head>
<body>

<?= $this->include('templates/menu'); ?>

<script type="text/javascript">
    var baseUrl = '<?= base_url() ?>';

    // Convertimos los datos de PHP a un formato JSON que JavaScript pueda entender
    var turnosCompletoData = <?php echo json_encode($listadoTurnosCompleto); ?>;
    
    </script>

</body>
</html>
