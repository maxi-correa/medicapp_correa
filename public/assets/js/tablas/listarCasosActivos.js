$(document).ready(function () {

    $('#tableActivos').DataTable({
        //configuraciones de la tabla
        language: {
            emptyTable: "No hay casos activos",
        },
        responsive: true,
        data: casosActivosData,
        searching: false,
        pageLength: 5,
        lengthChange: false,
        destroy: true,
        info: false,
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
                render: function (data, type, row) {
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
                data: 'tipoCategoriaVigente', visible: false
            },

            {
                data: 'tiposeveridad'
            },

            {
            
                data: 'fechaSugeridaTurno',
                render: function (data, type, row) {
                    const hoy = moment().format('DD/MM/YYYY');
                    return hoy;
                    return data ? moment(data).format("DD/MM/YYYY") : hoy; // Formato con Moment.js
                }
            },

            {
                data: null,
                render: function (data, type, row) {
                    return "<button class='verde btn-accion btn-redirigir' data-numeroTramite='" + row.numeroTramite + "'>Ver</button>";

                }
            }
        ],

        columnDefs: [
            { orderable: false, targets: -1 } // -1 hace referencia a la última columna
        ]

    });

    $('#tableActivos tbody').on('click', '.btn-redirigir', function () {
        let numeroTramite = $(this).attr('data-numeroTramite');
        window.location.href = baseUrl + 'guardarNumeroTramite/' + numeroTramite;

    });

});

