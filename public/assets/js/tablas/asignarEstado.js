$(document).ready(function() {

  $('#estadoCertificado').change(function() {

    let valorSeleccionado = $(this).val();
    
    if (valorSeleccionado === '4') {
      $('#id-area-ae').show(); // Mostrar el textarea
    } else {
      $('#id-area-ae').hide(); // Ocultar el textarea
    }
  });

  $('#formAsignarEstado').on('submit', function(event) {

    $('#errorJustificacion').html("");
    $('#errorRazon').html("");
    
    let inputJustificacion = $('#estadoCertificado').val();
    let inputRazon = $('#razon').val();

    let error = false;

      if (inputJustificacion==0) {
        $('#errorJustificacion').html( "Seleccione una justificación" );
        error =true;
      } 
      
      if(inputJustificacion==4 && inputRazon=="") {
        $('#errorRazon').html( "Escriba la razon de injustificación correspondiente" );
        error =true;
      }

      if (error) {
      event.preventDefault(); // Evita el envío y la recarga de la página
    }
      
    });

  

})

