console.log("CARGA");

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btna').addEventListener('click', function() {       
        
       var url = this.getAttribute('data-url'); // Obtener la URL desde el atributo data-url

            // Realizar la solicitud AJAX
            fetch(url)
            .then(response => {
                // Verifica el status de la respuesta (por ejemplo, 200 OK)
                console.log(response);  // Depuración de la respuesta
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json(); // Convertir la respuesta en JSON
            })
            .then(data => {
                console.log(data);  // Depuración del JSON recibido
                if (data.success) {
                    location.reload();
                } else {
                    alert("Hubo un error al deshabilitar al médico: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error); // Mostramos el error en consola
                alert("Error en la solicitud: " + error.message);
            });
    });

});


/*       document.getElementById('btnDeshabilitar').addEventListener('click', function() {
            var cantidadDias = document.getElementById('cantidadDias').value;

            // Verificar que se haya ingresado un número mayor a 0
            if (cantidadDias > 0) {
                var matricula = "<?= $medico['matricula'] ?>"; // Obtener la matrícula del médico de PHP

                // Construir la URL con los parámetros
                var url = "<?= base_url('medicos/deshabilitarTurnos') ?>/" + matricula + "/" + cantidadDias;

                // Realizar la solicitud GET
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mostrar el mensaje de éxito directamente en la vista
                            const successMessage = document.getElementById("mensajeResultado");
                            successMessage.className = 'alert alert-success';
                            successMessage.textContent = 'Los turnos han sido reasignados correctamente. <br> El medico está inhabilitado para recibir turnos nuevos';
                            mensajeContenedor.appendChild(successMessage);
                            document.body.appendChild(successMessage);
                        } else {
                            alert("Hubo un error al deshabilitar los turnos");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        //alert("Error en la solicitud");
                    });
            } else {
                // Prevenir que el formulario se envíe
                event.preventDefault();
                // Si no se ingresa una cantidad válida de días, mostrar el error en el contenedor con id="mensaje"
                const errorMessage = document.getElementById('mensajeResultado');
                errorMessage.classList.add('alert', 'alert-danger');
                errorMessage.textContent = 'Ingrese una cantidad válida de días';
            }
        });*/