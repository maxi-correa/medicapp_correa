
<!-- Menú de navegación -->

<nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <h1 class="navbar-brand ms-3">MEDICAPP</h1>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <?php
            $menu = session()->get('menu');
            if (!empty($menu)):
                foreach ($menu as $item): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $item['url'] ?>"><?= $item['label'] ?></a>
                    </li>
                <?php endforeach;
            else: ?>
                <li class="nav-item"><a class="nav-link">No hay opciones de menú disponibles.</a></li>
            <?php endif; ?><li class="nav-item">
            <li class="nav-item">
                <span class="nav-link"><?php echo session()->get('nombre'); ?> <?php echo session()->get('apellido'); ?></span>
            </li>
            <li class="nav-item">
                <span class="nav-link"><?php echo session('rol'); ?></span>
            </li>
            <li class="nav-item position-relative me-3">
                <a class="nav-link <?= session('tieneNotificaciones') ? '' : 'disabled' ?>" href="<?= session('tieneNotificaciones') ? base_url('notificaciones') : '#' ?>">
                    <i class="fas fa-bell"></i>
                    
                    <?php if (session('tieneNotificaciones')): ?>
                    <span
                        class="position-absolute bg-danger rounded-circle"
                        style="width:8px; height:8px;">
                    </span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('salir')?>"><i class="fas fa-sign-out-alt"></i></a>
            </li>
        </ul>
    </div>
</nav>

