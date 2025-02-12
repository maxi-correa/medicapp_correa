$(document).ready(function() {
    
    $('#tableSeguimiento').DataTable( {
        //configuraciones de la tabla
        language: {
            emptyTable: "No hay observaciones"
        },
        responsive: true,
        data: datosSeguimientos,
        searching: false,
        pageLength: 5,    // Muestra 5 filas por página sin permitir cambiarlo
        lengthChange: false,
        destroy: true,
        info: false,
        columns: [
            {
                data: null,
                render: function(data, type, row) {
                    fechaTurno = row.fecha ? moment(row.fecha).format("DD/MM/YYYY") : '';
                    horaTurno = row.hora ? moment(row.hora, "HH:mm:ss").format("HH:mm") : '';
                    return "<strong>" + fechaTurno + ' - ' + horaTurno + "</strong>";
                }
            },
            
            {
                data: 'lugar'
            },

            {
                data: 'medico'
            },
    
            {
                data: 'observacion'
            },
    
            {
                data: 'tipoSeguimiento'
            },
            {
                render: function(data, type, row) {
                    return " ";
                }
            }
        ],
        columnDefs: [
            { orderable: false, targets: -1 } // -1 hace referencia a la última columna
        ]
    
    });

    $('#tablePendientes tbody').on('click', '.btn-redirigir', function() {
        var legajo = $(this).data('legajo'); // Obtener el legajo del atributo data-legajo
        window.location.href = baseUrl + 'unCaso/' + legajo;
       
    });

});

