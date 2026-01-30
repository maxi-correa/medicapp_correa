$(document).ready(function () {

    $('#tableHistorial').DataTable({
        language: {
            emptyTable: "No hay casos finalizados"
        },
        responsive: true,
        data: datosHistorial,
        searching: false,
        pageLength: 5,
        lengthChange: false,
        destroy: true,
        info: false,

        columns: [
            { data: 'idCertificado', visible: false },

            { data: 'numeroTramite' },

            { data: 'tipoCertificado' },

            {
                data: 'fechaFin',
                render: function (data) {
                    return data ? moment(data).format("DD/MM/YYYY") : '';
                }
            },

            { data: 'disponeCertificado' },

            {
                data: 'idCertificado',
                render: function (data, type, row) {
                    if (!data) return '';
                    return `
                        <a class="bg-transparent" 
                        href="${baseUrl}download/${row.legajo}/${data}">
                            <img class="img_asignar_estado"
                                src="${baseUrl}assets/img/icono_descargar_certificado.png">
                        </a>`;
                }
            },

            {
                data: 'idEstado',
                render: function (data) {
                    if (data == 4) return 'Injustificado';
                    if (data == 5) return 'Justificado';
                    return '';
                }
            },

            { data: 'tiposeveridad' },

            {
                data: 'tiposeveridad',
                render: function (data) {
                    return data === 'Complejo' ? 'SI' : 'NO';
                }
            },

            {
                render: function () {
                    return '';
                }
            }
        ],

        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});