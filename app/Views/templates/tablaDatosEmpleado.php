<div class="container">
    <div class="encabezado-blanco text-center texto-negro raleway fw-bold display-6">
        <?php echo $titulo;  ?>
    </div>
    <div class="table-responsive-md">
        <table class="tabla-empleado">
            <thead>
                <tr>
                    <th rowspan="2">
                        <img class="img-tabla-empleado" src="<?= base_url('assets/img/icono_persona.png');?>" alt="">
                    </th>   
                    <?php foreach ($datosEmpleado as $unDato): ?>
                    <th rowspan="2"> <?php echo $unDato->empleado; ?></th>
                    <?php endforeach; ?>  
                    <th>PROPIO/FAMILIAR</th>
                    <th>FECHA DE INICIO DEL CASO</th>
                    <th>MOTIVO</th>
                    <?php if ($datosExtra){ ?>
                    <th>GRAVEDAD</th>
                    <th>LUGAR DE REPOSO</th>
                    <?php } ?>
                </tr>
                <tr>
                <?php foreach ($datosEmpleado as $unDato): ?>
                    <th> <?php echo $unDato->tipoCertificado; ?> 
                        <?php if (isset($unDato->pacienteFamiliar)) {
                               echo "<br>".$unDato->pacienteFamiliar; 
                        } ?>
                    </th>
                    <th> <?php 
                    $fecha = new DateTime($unDato->fechaAusencia);
                    echo $fecha->format("d/m/Y");
                    ?></th>
                    <th> <?php echo $unDato->motivo; ?></th>
                <?php endforeach; ?>
                <?php if ($datosExtra){ ?>
                    <?php foreach ($lugarCertificado as $unLugar): ?>
                    <th> <?php echo $unDato->tiposeveridad; ?></th>
                    <th> <?php echo $unLugar->lugarReposo; ?></th>
                    <?php endforeach; ?>
                <?php } ?>
                
                </tr>
            </thead>
            <?php if ($footerTabla){ ?>
            <tfoot class="gris">
                <tr class="text-end">
                    <td colspan="7 "><button class="btn btn-verde font-weight-bold" onclick="window.location.href='<?= base_url('certificado');?>'">SUBIR CERTIFICADO</button></td>
                </tr>
            </tfoot>  
            <?php } ?>
        </table>
    </div>    
</div>
