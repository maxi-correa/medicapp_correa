$(document).ready(function() {
    
    //'ID TURNO','PACIENTE', 'HORA', 
    //    'DIRECCION', 'NOMBRE', 'APELLIDO', 'DISPONE DE CERTIFICADO')];
    $('#tableTurnosCompleto').DataTable( {
        //configuraciones de la tabla
        responsive: true,
        data: casosPendientesData,
        searching: false,
        pageLength: 5,    // Muestra 5 filas por página sin permitir cambiarlo
        lengthChange: false,
        destroy: true,
        info: false,
        columns: [
            {
                data: 'idTurno', visible: false
            },
            
            {
                data: 'paciente'
            },
    
            {
                data: 'hora'
            },
    
            {
                data: 'lugar'
            },
    
            { 
                data: 'numeroTramite',
                visible: false,  // Oculta la columna en la tabla
                title: "NRO TRAMITE"
              },

            {
                data: null, // No hay dato directo, se genera el contenido de la celda
                render: function (data, type, row) {

                        return "<button class='btn btn-verde btn-accion btn-redirigir' data-numeroTramite='" + row.numeroTramite + "' data-idTurno='"+row.idTurno+"'>Ver</button>";
                
                }
            }

            
        ]
    
    });

    $('#tableTurnosCompleto tbody').on('click', '.btn-redirigir', function() {
        var numeroTramite = $(this).attr('data-numeroTramite'); // Obtener el legajo del atributo data-numeroTramite
        var idTurno = $(this).attr('data-idTurno'); // Obtener el legajo del atributo data-idTurno
        window.location.href = baseUrl + 'unCaso/' + numeroTramite + '/' + idTurno + '/3';
       
    });

});

