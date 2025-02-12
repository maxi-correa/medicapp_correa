
$(document).ready(function(){
    console.log("El archivo js se cargo correctamente");
});

$(document).ready(function() {
    // Cargar los casos al iniciar la página
    obtenerCasos();

    // Función para obtener casos según las fechas seleccionadas
    function obtenerCasos() {
      const fechaInicio = $('input[name="fechaInicio"]').val();
      const fechaFin = $('input[name="fechaFin"]').val();

      $.ajax({
        url: "<?= base_url('reporte/prueba') ?>", // Cambia esto según tu ruta
        type: 'GET',
        data: {
          fechaInicio: fechaInicio,
          fechaFin: fechaFin
        },
        dataType: 'json',
        success: function(data) {
          // Aquí debes procesar los datos recibidos y actualizar la tabla
          let tbody = '';
          let totalSector = 0;
          let sectorActual = '';

          $.each(data, function(index, caso) {
            // Comprobar si hemos cambiado de sector
            if (sectorActual !== caso.sector && sectorActual !== '') {
              tbody += `<tr class="thead-azul-claro" colspan="7">
                          <td colspan="7" style="text-align: right;">
                            Total de ausencias en ${sectorActual}: ${totalSector}
                          </td>
                        </tr>`;
              totalSector = 0; // Reiniciar el contador de total de ausencias por sector
            }

            // Cambiar al nuevo sector y actualizar el total
            sectorActual = caso.sector;
            totalSector += caso.Cantidaddeausencias;

            tbody += `<tr>
                        <td>${caso.legajo}</td>
                        <td>${caso.sector}</td>
                        <td>${caso.nombre}</td>
                        <td>${caso.apellido}</td>
                        <td>${caso.Cantidaddeausencias}</td>
                      </tr>`;
          });

          // Mostrar el total de ausencias para el último sector
          tbody += `<tr class="thead-azul-claro" colspan="7">
                      <td colspan="7" style="text-align: right;">
                        Total de ausencias en ${sectorActual}: ${totalSector}
                      </td>
                    </tr>`;

          // Actualizar el cuerpo de la tabla
          $('tbody').html(tbody);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.error('Error al obtener los casos:', textStatus, errorThrown);
        }
      });
    }

    // Actualizar los casos cuando cambien las fechas
    $('input[name="fechaInicio"], input[name="fechaFin"]').on('change', function() {
      obtenerCasos();
    });
  });