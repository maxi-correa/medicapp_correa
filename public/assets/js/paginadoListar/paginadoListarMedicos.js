$(document).ready(function() {
    

    $('#tableListarMedicosAuditores').DataTable( {
        //configuraciones de la tabla
        language: {
            emptyTable: "No hay medicos cargados"
        },
        responsive: true,
        searching: false,
        pageLength: 10,    // Muestra 5 filas por página sin permitir cambiarlo
        lengthChange: false,
        destroy: true,
        info: false,
    })
      
});

