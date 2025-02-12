-
<div class="container">
    <div class="encabezado-gris">
      <div class="encabezado-titulo-lila raleway d-flex justify-content-center align-items-center fw-bold texto-blanco"><?php echo $estado;  ?></div>
    </div>
    <div class="table-responsive-md">
      <table id="<?php echo $idTabla; ?>" class="table table-hover texto-mediano raleway text-center relaway">
        <thead id="table-header">
            <tr class="celeste">
            <?php foreach ($headers as $header): ?>
                    <th class=""><?php echo $header; ?></th>
                <?php endforeach; ?>
                <th><button class="toggle-tbody btn" onclick="desplegar('<?php echo $idBody; ?>');">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                </svg>
                </button></th>
            </tr>
        </thead>
        <tbody id="<?php echo $idBody; ?>">
           
        </tbody>
      </table>
    </div>
  </div>

 


<!-- Otros scripts -->
<script src="<?= base_url($url);?>"></script>

