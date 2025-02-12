$(document).ready(function() {

    $('#tableReporteAusentismo').DataTable({
        language: {
            emptyTable: "No hay datos cargados"
        },
        responsive: true,
        data: reporteAusentismo,
        searching: false,
        pageLength: 15,    // Muestra 5 filas por página sin permitir cambiarlo
        lengthChange: false,
        destroy: true,
        info: false,
        columns: [
            { 
                data: 'legajo' 
            },
            { 
                data: 'sector' 
            },
            { 
                data: 'nombre' 
            },
            { 
                data: 'apellido' 
            },
            { 
                data: 'ausencias' 
            }
  
        ],
        order: [['sector', 'asc']],
        rowGroup: {
            startRender: null,
            endRender: function (rows, group) {
                let avg = 
                rows 
                    .data()
                    .map(function (row) {
                        return parseInt(row.ausencias)
                    }).reduce(function (a, b) {
                        return a + b;
                    }, 0);

                    // return 'Total de ausencias por sector:' + DataTable.render.number(null, null, 0, '').display(avg)
                    let total = 'Total de ausencias por sector: ' + 
                    DataTable.render.number(null, null, 0, '').display(avg);

                    return $('<tr/>')
                    .append('<td colspan="5" style="background-color: #e4e3df; font-weight: bold; text-align: right;">' + total + '</td>');
                
            },
            dataSrc: 'sector'
        },

    });

    

    
    $('#fechaDesde, #fechaHasta').on('change', function() {
        let fechaDesde = $('#fechaDesde').val();
        let fechaHasta = $('#fechaHasta').val();
        window.location.href = base_url + "reportes/ausentismo/" + fechaDesde + "/" + fechaHasta;
    });


    $('#btnExportar').on('click', function() {
        let fechaDesde = $('#fechaDesde').val();
        let fechaHasta = $('#fechaHasta').val();
        window.location.href = base_url + "reportes/ausentismo/" + fechaDesde + "/" + fechaHasta;
    });
});
