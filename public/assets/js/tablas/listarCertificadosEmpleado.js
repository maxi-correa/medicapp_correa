$(document).ready(function() {
    

    $('#tableCertificado').DataTable( {
        //configuraciones de la tabla
        language: {
            emptyTable: "No hay certificados cargados"
        },
        responsive: true,
        data: datosCertificados,
        // data: datosEjemplos,
        searching: false,
        pageLength: 5,    // Muestra 5 filas por página sin permitir cambiarlo
        lengthChange: false,
        destroy: true,
        info: false,
        columns: [
            {
                data: 'idCertificado', visible: false
            },
            {
                data: 'nombre'
            },
    
            {
                data: 'fechaEmision',
                render: function(data, type, row) {
                    return data ? moment(data).format("DD/MM/YYYY") : ''; // Formato con Moment.js
                }
            },
    
            {
                data: 'diasOtorgados', 
                render: function(data, type, row) {
                    return data ? moment(data).format("DD/MM/YYYY") : ''; // Formato con Moment.js
                }
            },
    
            {
                data: 'lugarReposo'
            },
    
            {
                data: 'estado'
            },
            
            {
                data: 'descripcion'
            },
            
            {
                render: function(data, type, row) {
                    return "<a id='btn-descargar-certificado' class='bg-transparent' href='"+ site_url+"/download/"+ legajo +"/"+row.idCertificado+"'> <img class='img_asignar_estado' src='" + baseUrl + "assets/img/icono_descargar_certificado.png'> </a>  ";


                    }
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


    if (btnSubirCertificado) {
        $('#footerTableCertificado').append(`<tr><td colspan='8'><button class='btn btn-verde font-weight-bold' onClick='window.location.href ="${baseUrl}certificado"'>SUBIR CERTIFICADO</button></td></tr>`);
      } else {
        $('#footerTableCertificado').append(`<tr><td colspan='8'><button disabled class='btn gris-oscuro font-weight-bold' onClick='window.location.href ="${baseUrl}certificado"'>SUBIR CERTIFICADO</button></td></tr>`);
      }

});

