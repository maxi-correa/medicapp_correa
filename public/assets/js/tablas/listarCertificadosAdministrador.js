$(document).ready(function () {
  $("#tableCertificado").DataTable({
    //configuraciones de la tabla
    language: {
      emptyTable: "No hay certificados cargados",
    },
    responsive: true,
    data: datosCertificados,
    searching: false,
    pageLength: 5, // Muestra 5 filas por página sin permitir cambiarlo
    lengthChange: false,
    destroy: true,
    info: false,
    columns: [
      {
        data: "idCertificado",
        visible: false,
      },
      {
        data: "nombre",
      },

      {
        data: "fechaEmision",
        render: function (data, type, row) {
          return data ? moment(data).format("DD/MM/YYYY") : ""; // Formato de fecha
        },
      },

      {
        data: "diasOtorgados",
        render: function (data, type, row) {
          return data ? moment(data).format("DD/MM/YYYY") : "";
        },
      },

      {
        data: "lugarReposo",
      },

      {
        // data: null,
        render: function (data, type, row) {
          return (
            "<a id='btn-descargar-certificado' class='bg-transparent' href='" +
            site_url +
            "/download/" +
            legajo +
            "/" +
            row.idCertificado +
            "'> <img class='img_asignar_estado' src='" +
            baseUrl +
            "assets/img/icono_descargar_certificado.png'> </a>  "
          );
        },
      },

      {
        data: "estado",
      },

      {
        data: "matricula",
        visible: false,
      },

      {
        data: "medico",
        visible: false,
      },

      {
        data: null,
        render: function (data, type, row) {
          if (row.estado == "PENDIENTE DE REVISION") {
            return (
              "<button type='button' class='btn btn-verde btn-abrir-modal' data-idCertificado='" +
              row.idCertificado +
              "' data-nombre='" +
              row.nombre +
              "' data-desde='" +
              row.fechaEmision +
              "' data-hasta='" +
              row.diasOtorgados +
              "' data-matricula='" +
              row.matricula +
              "' data-medico='" +
              row.medico +
              "' data-legajo='" +
              legajo +
              "'  data-bs-toggle='modal' data-bs-target='#exampleModal'>" +
              "<img class='img_asignar_estado' src='" +
              baseUrl +
              "assets/img/asignar_estado.png'> </button>"
            );
          } else {
            return (
              "<button disabled type='button' class='btn gris-oscuro btn-abrir-modal'  data-bs-toggle='modal' data-bs-target='#exampleModal'>" +
              "<img class='img_asignar_estado' src='" +
              baseUrl +
              "assets/img/asignar_estado.png'> </button>"
            );
          }
        },
      },
    ],
    columnDefs: [
      { orderable: false, targets: -1 }, // -1 hace referencia a la última columna
    ],
  });

  $(document).on("click", ".btn-abrir-modal", function () {
    // Obtener el id del atributo data-id
    let diagnostico = $(this).attr("data-nombre");
    let desde = $(this).attr("data-desde");
    desde = desde ? moment(desde).format("DD/MM/YYYY") : "";
    let hasta = $(this).attr("data-hasta");
    hasta = hasta ? moment(hasta).format("DD/MM/YYYY") : "";
    let matricula = $(this).attr("data-matricula");
    let medico = $(this).attr("data-medico");
    let id = $(this).attr("data-idCertificado");
    let legajo = $(this).attr("data-legajo");
    console.log(id);

    $("#tdDesde").text(desde);
    $("#tdHasta").text(hasta);
    $("#tdRazonCertificado").text(razonDelCertificado);
    $("#tdPaciente").text(paciente);
    $("#tdDni").text(dni);
    $("#tdDiagnostico").text(diagnostico);
    $("#tdMatricula").text(matricula);
    $("#tdMedico").text(medico);
    $("#idCertificado").val(id);
    console.log($("#idEstado").val(id));

    $("#desdeForm").val(desde);
    $("#hastaForm").val(hasta);

    let image = site_url + "certificado/" + legajo + "/" + id;
    $("#img-certificado-id").attr("src", image);
  });

  if (btnRegistrarTurnoCMC && btnRegistrarTurnoTA) {
    $("#footerTableCertificado").append(
      `<tr><td colspan='7'><button class='btn btn-verde font-weight-bold' onClick='window.location.href ="${baseUrl}/registrar-turno"'>REGISTRAR TURNO</button></td></tr>`
    );
  } else {
    $("#footerTableCertificado").append(
      `<tr><td colspan='7'><button disabled class='btn gris-oscuro font-weight-bold' onClick='window.location.href ="${baseUrl}/registrar-turno"'>REGISTRAR TURNO</button></td></tr>`
    );
  }
});
