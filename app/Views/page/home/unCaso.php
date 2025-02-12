<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar caso</title>
    

    <link href="<?= base_url('assets/css/sb-admin-2.min.css')?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap.css')?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/app-style-tabla.css')?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/colores.css')?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/texto.css')?>" rel="stylesheet">
</head>
<body>
<?= $this->include('templates/menu'); ?>

<?php if (session()->get('rol')=="Admin. Medicina Laboral") { ?>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="d-flex justify-content-center">
          <div class="certificado-contenedor px-2 mr-4">
          <table id="tabla-asignar-estado-certificado " class="table">
          <thead>
              <tr>
                <td colspan='2' class='text-center azul texto-blanco fs-5 font-weight-bold'>CERTIFICADO</td>
              </tr>
            </thead>
            <tbody>
              <tr>
              <tr>
                <td class='font-weight-bold'>DESDE</td>
                <td><span id="tdDesde"></span></td>
              </tr>
              <tr>
                <td class='font-weight-bold'>HASTA</td>
                <td><span id="tdHasta"></span></td>
              </tr>
              <tr>
                <td class='font-weight-bold'>RAZON DEL CERTIFICADO</td>
                <td><span id="tdRazonCertificado"></span></td>
              </tr>
              <tr>
                <td class='font-weight-bold'>PACIENTE</td>
                <td><span id="tdPaciente"></span></td>
              </tr>
              <tr>
              <tr>
                <td class='font-weight-bold'>DNI</td>
                <td><span id="tdDni"></span></td>
              </tr>
              <tr>
                <td class='font-weight-bold'>DIAGNOSTICO</td>
                <td><span id="tdDiagnostico"></span></td>
              </tr>
              <tr>
                <td colspan='2' class='text-center azul texto-blanco fs-5 font-weight-bold'>MEDICO TRATANTE</td>
              </tr>
              <tr>
                <td class='font-weight-bold'>NOMBRE Y APELLIDO</td>
                <td><span id="tdMedico"></span></td>
              </tr>
              <tr>
                <td class='font-weight-bold'>MATRICULA</td>
                <td><span id="tdMatricula"></span></td>
              </tr>
            </tbody>
            </table>
          </div>
          <div class="certificado-contenedor">
            <img id="img-certificado-id" class= "img-certificado" alt="Certificado">
          </div>
        </div>

        <!-- FORMULARIO -->
        <div class="form-contenedor d-flex justify-content-center">
          
        <form id="formAsignarEstado" action="<?= base_url('/certificado/update');?>" method="POST" class="w-75 p-3">
            <div class="form-group">

              <!-- DATOS DEL CERTIFICADO -->
            <input type="hidden" name="desdeForm" id="desdeForm">
            <input type="hidden" name="hastaForm" id="hastaForm">
            <?php if (isset($datosCertificados[0]->numeroTramite)) {
              ?> 
              <input type="hidden" name="nroTramite" id="nroTramite" value="<?= $datosCertificados[0]->numeroTramite ?>">
            <?php } else { ?>
              <input type="hidden" name="nroTramite" id="nroTramite" value="">
            <?php } ?>
            
            <input type="hidden" name="idCertificado" id="idCertificado">
            <input type="hidden" name="legajoForm" id="legajoForm" value="<?= $datosEmpleado[0]->legajo ?>">
            
              <!-- -------- -->

              <label class='font-weight-bold'>ESTADO: </label>
              <select id="estadoCertificado" name="estadoCertificado" class="form-select">
                <option value="0">Seleccione</option>
              <?php foreach ($estadosCertificados as $unEstado): ?>
                <option value="<?php echo $unEstado->idEstado; ?>"><?php echo $unEstado->estado; ?></option>
                <?php endforeach; ?>  
              </select>
              <span id="errorJustificacion"></span>
            </div>
           <div class="form-group">
            <div id="id-area-ae">
              <label class='font-weight-bold'>RAZÓN: </label>
              <textarea id="razon" name="razon" class="form-control"></textarea>
              <span id="errorRazon"></span>
            </div>
           </div>
           <div class="modal-footer">
        <button type="button" class="btn btn-rojo" data-dismiss="modal">Cancelar</button>
    
        <button id="btn-confirmar-estado" type="submit" value="CONFIRMAR" class="btn btn-verde" >
              Confirmar</button>
      </div>
           </form>
        </div>
      </div>
      
      
    </div>
  </div>
</div>
<?php } ?>
<?php if (session()->get('rol')=="Admin. Medicina Laboral") {
    $session = session();
    $session->set('numeroTramite', $datosEmpleado[0]->numeroTramite);
     } ?>

<script type="text/javascript">
    var baseUrl = '<?= base_url() ?>';
    var site_url = '<?= site_url() ?>'
    <?php if (session()->get('rol')=="Admin. Medicina Laboral" || session()->get('rol')=="Empleado Común") { ?>
    var legajo = <?=$datosEmpleado[0]->legajo?>;
    <?php } 
    if (session()->get('rol')=="Admin. Medicina Laboral"){ ?>
      var razonDelCertificado = "<?=$datosEmpleado[0]->tipoCertificado ?>";
      var paciente = "<?= $datosEmpleado[0]->tipoCertificado=='Familiar' ? $datosEmpleado[0]->pacienteFamiliar : $datosEmpleado[0]->empleado?>";
      var dni= "<?=$datosEmpleado[0]->dniPaciente?>";
    <?php } ?>
  
    var datosCertificados = <?php echo json_encode($datosCertificados); ?>;
    var datosSeguimientos = <?php echo json_encode($datosSeguimientos); ?>;
</script>
<script src="<?= base_url('assets/js/tablas/asignarEstado.js')?>"></script>
</body>
</html>