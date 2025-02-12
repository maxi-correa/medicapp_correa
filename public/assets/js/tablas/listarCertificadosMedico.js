    $(document).ready(function () {


    $('#tableCertificado').DataTable({
        //configuraciones de la tabla
        language: {
            emptyTable: "No hay certificados cargados"
        },
        responsive: true,
        data: datosCertificados,
        searching: false,
        pageLength: 5,
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
                render: function (data, type, row) {
                    return data ? moment(data).format("DD/MM/YYYY") : ''; // Formato de fecha
                }
            },

            {
                data: 'diasOtorgados',
                render: function (data, type, row) {
                    return data ? moment(data).format("DD/MM/YYYY") : '';
                }
            },

            {
                data: 'lugarReposo'
            },

            {
                data: 'medicoTratante'
            },
            {
                render: function (data, type, row) {
                    return "";
                }
            }

        ],
        columnDefs: [
            { orderable: false, targets: -1 } // -1 hace referencia a la última columna
        ]
    });
});

