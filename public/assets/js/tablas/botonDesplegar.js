    
    function desplegar(tableBody) {
   
        let tbody = $('#' + tableBody);

        if (tbody.is(':visible')) {
            tbody.slideUp(300); // Oculta el tbody
        } else {
            tbody.slideDown(300); // Muestra el tbody
        }
    }
        
