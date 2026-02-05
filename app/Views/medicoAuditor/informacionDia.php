<!DOCTYPE html>
<html lang="es">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedicApp</title>
    <head>
        <!-- Enlace a Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/sb-admin-2.min.css');?>" >
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.css');?>" >
    
        <!-- Enlace a Font Awesome para el icono de cierre de sesión -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- Enlace al css de dataTables -->
        <link rel="stylesheet" href="<?= base_url('assets/css/dataTable.css');?>">

        <!-- Enlaces a los archivos CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/variables.css');?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/divs.css');?>" >    
        <link rel="stylesheet" href="<?= base_url('assets/css/layout.css');?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/buttons.css');?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/colores.css');?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/texto.css');?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/app-style-menu.css');?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/app-style-tabla.css');?>">
        <link rel="stylesheet" href="<?= base_url('assets/css/app-style-ventana-modal.css')?>">
        <style>
        .encabezado {
            display: flex;
            align-items: center;
        }
        .encabezado a {
            margin-right: auto;
        }
        .encabezado .encabezado-blanco {
            flex-grow: 1;
            text-align: center;
        }
        .modal-backdrop {
            display: none !important; /* Ocultar el fondo negro de la ventana modal */
        }
    </style>
    </head>
    
    <body>
    <header class="header margin-10">
        <?= view('templates/menu'); ?>
    </header>

    <!--############# CONTENIDO DE LA PÁGINA ########################### !-->
    <div class="container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <div class="relaway text-center"><h1>HORARIO DÍA: <?= esc($dia) ?></h1></div>
    </div>

    <div class="container">
        <div class="encabezado-azul raleway d-flex justify-content-center align-items-center fw-bold texto-blanco">
            <h2>MÉDICO <?php echo esc($nombre['nombre']);?> <?php echo esc($nombre['apellido'])?> (<?= esc($matricula); ?>)</h2>
        </div>
        <div style="display:flex; justify-content: space-between;">
        <div>
            <a href="<?= site_url('medicos/informacion/' . esc($matricula)) ?>">
                <button class='rojo'>Atrás</button>
            </a>
        </div>
        <div>
            <a href="<?= site_url('medicos/informacion/' . esc($matricula) . '/' . urlencode($dia) . '/vacio') ?>">
            <button class="verde btn-accion btn-redirigir"> Agregar </button>
            </a>
            <button id="btnQuitar" class="gris-oscuro btn-accion btn-redirigir" disabled> Quitar </button>
        </div>
        </div>
<table class="table table-hover texto-mediano raleway text-center  relaway w-100 p-3 ">
    <thead>
        <tr class="celeste">
            <th></th>
            <th>HORA DE INICIO</th>
            <th>HORA DE FIN</th>
            <th>DURACIÓN</th>
        </tr>
    </thead>
    <tbody class="text-center">
        <?php if (!empty($horarios)): ?>
            <?php foreach ($horarios as $horario): ?>
                <tr>
                    <td>
                        <input type="radio" name="horarioSeleccionado" class="radio-horario"
                            data-id="<?= esc($horario['idHorario']) ?>"
                            data-matricula="<?= esc($horario['matricula']) ?>"
                            data-inicio="<?= esc($horario['horaInicio']) ?>"
                            data-fin="<?= esc($horario['horaFin']) ?>"
                            data-dia="<?= esc($dia) ?>">
                    </td>
                    <td><?= esc($horario['horaInicio']) ?></td>
                    <td><?= esc($horario['horaFin']) ?></td>
                    <td><?= esc($horario['duracion']) ?> minutos</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No hay horarios disponibles para este día.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<!-- Modal de confirmación para quitar horario -->
<div id="modalQuitar" class="modal">
    <div class="modal-content">
        <span class="close" id="cerrarModal">&times;</span>
        <h3 class="borde texto-blanco azul">Confirmar eliminación</h3>
        <p id="mensajeModal"></p>
        <div class="contenido-centrado">
            <button id="confirmarQuitar" class="verde">Confirmar</button>
            <button id="cancelarQuitar" class="rojo">Cancelar</button>
        </div>
    </div>
</div>

<!-- Formulario oculto para enviar la solicitud de eliminación -->
<form id="formQuitar" method="post" action="<?= site_url('medicos/horarios/eliminar') ?>">
    <input type="hidden" name="idHorario" id="inputIdHorario">
    <input type="hidden" name="matricula" id="inputMatricula" ?>
    <input type="hidden" name="dia" id="inputDia" ?>
</form>

<?= $this->include('templates/footer'); ?>
</body>

<!-- Script para manejar el modal de confirmación -->
<script>
const modal = document.getElementById('modalQuitar');
const mensajeModal = document.getElementById('mensajeModal');

btnQuitar.addEventListener('click', () => {
    if (!horarioSeleccionado) return;

    mensajeModal.innerHTML = `
        <strong>Día:</strong> ${horarioSeleccionado.dia}<br>
        <strong>Horario:</strong> ${horarioSeleccionado.inicio} - ${horarioSeleccionado.fin}<br><br>
        ¿Está seguro que desea eliminar este horario?
    `;

    modal.style.display = 'block';
});

document.getElementById('cerrarModal').onclick =
document.getElementById('cancelarQuitar').onclick = () => {
    modal.style.display = 'none';
};
</script>

<!-- Script para manejar la selección del horario y habilitar el botón Quitar -->
<script>
let horarioSeleccionado = null;
const btnQuitar = document.getElementById('btnQuitar');

document.querySelectorAll('.radio-horario').forEach(radio => {
    radio.addEventListener('change', function () {
        horarioSeleccionado = {
            id: this.dataset.id,
            inicio: this.dataset.inicio,
            fin: this.dataset.fin,
            matricula: this.dataset.matricula,
            dia: this.dataset.dia
        };
        btnQuitar.disabled = false;

        // Cambiar estilos
        btnQuitar.classList.remove('gris-oscuro');
        btnQuitar.classList.add('rojo');
    });
});
</script>

<!-- Script para enviar el formulario de eliminación al confirmar -->
<script>
document.getElementById('confirmarQuitar').addEventListener('click', () => {
    document.getElementById('inputIdHorario').value = horarioSeleccionado.id;
    document.getElementById('inputMatricula').value = horarioSeleccionado.matricula;
    document.getElementById('inputDia').value = horarioSeleccionado.dia;
    document.getElementById('formQuitar').submit();
});
</script>

</html>