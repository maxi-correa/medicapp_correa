console.log("funca");

// Detectar cambios en los filtros
$(document).ready(function() {
    // Cuando cambia el valor de alguno de los filtros
    $('#fechaDesde, #fechaHasta, #selectMedicos').change(function() {
        // Obtener los valores de los inputs
        var fechaDesde = $('#fechaDesde').val();
        var fechaHasta = $('#fechaHasta').val();
        var medico = $('#selectMedicos').val();

        // Verificar que los tres parámetros estén presentes
        if (fechaDesde && fechaHasta && medico) {
            // Si están presentes, hacer la consulta AJAX
            $.ajax({
                url: "certificadosPeriodo", // La URL de la función del controlador
                type: 'GET', // Método GET para enviar los datos
                data: {
                    fechaDesde: fechaDesde,
                    fechaHasta: fechaHasta,
                    medico: medico
                },
                success: function(response) {
                    // Actualizar la tabla con los datos recibidos
                    var tbody = $('#tablaCertificados tbody');
                    tbody.empty(); // Limpiar la tabla

                    // Comprobar si hay datos
                    if (response.length > 0) {
                        // Iterar sobre los datos y agregarlos a la tabla
                        $.each(response, function(index, certificado) {
                            var row = '<tr>' +
                                        '<td>' + certificado.legajo + '</td>' +
                                        '<td>' + certificado.nombre + '</td>' +
                                        '<td>' + certificado.apellido + '</td>' +
                                        '<td>' + certificado.sector + '</td>' +
                                        '<td>' + certificado.cantidad_certificados + '</td>' +
                                      '</tr>';
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="4" class="text-center">No se encontraron certificados.</td></tr>');
                    }
                },
                error: function() {
                    alert('Hubo un error al obtener los datos.');
                }
            });
        } else {
            // Si falta algún parámetro, mostrar un mensaje o simplemente no hacer nada
            //alert('Debe seleccionar los tres parámetros: fechas y médico.');
        }
    });
});