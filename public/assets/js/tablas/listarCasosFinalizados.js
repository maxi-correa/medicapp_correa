$(document).ready(function() {
    
    $('#tableFinalizados').DataTable( {
        //configuraciones de la tabla
        language: {
            emptyTable: "No hay casos finalizados"
        },
        responsive: true,
        data: casosFinalizadosData,
        searching: false,
        pageLength: 5,    // Muestra 5 filas por página sin permitir cambiarlo
        lengthChange: false,
        destroy: true,
        info: false, // Esto desactiva la información sobre los registros
        columns: [
            {
                data: 'numeroTramite', visible: false
            },
            
            {
                data: 'legajo'
            },
    
            {
                data: 'tipoCertificado'
            },
    
            {
                data: 'fechaAusencia',
                render: function(data, type, row) {
                    return data ? moment(data).format("DD/MM/YYYY") : ''; // Formato con Moment.js
                }
            },
    
            {
                data: 'nombre'
            },
    
            {
                data: 'apellido'
            },
    
            {
                data: 'disponeCertificado'
            },
    
            {
                data: null, // No hay dato directo, se genera el contenido de la celda
                render: function (data, type, row) {

                        return "<button class='btn btn-verde btn-accion btn-redirigir' data-numeroTramite='" + row.numeroTramite + "'>Ver</button>";
                
                }
            }
        ],
        columnDefs: [
            { orderable: false, targets: -1 } // -1 hace referencia a la última columna
        ]
    
    });

    $('#tableFinalizados tbody').on('click', '.btn-redirigir', function() {
        var numeroTramite = $(this).attr('data-numeroTramite'); // Obtener el legajo del data-numeroTramite
        window.location.href = baseUrl + 'guardarNumeroTramite/' + numeroTramite;
    });

});

