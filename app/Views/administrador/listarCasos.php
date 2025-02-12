
<?= $this->include('templates/menu'); ?>

    <div class="container">
        <div class="relaway text-center"><h1>LISTA DE CASOS</h1></div>
    </div>

<script type="text/javascript">
    var baseUrl = '<?= base_url() ?>';
    var casosFinalizadosData = <?php echo json_encode($casosFinalizado); ?>;
    var casosPendientesData = <?php echo json_encode($casosPendientes); ?>;
    var casosActivosData = <?php echo json_encode($casosActivos); ?>;
</script>

