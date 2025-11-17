<nav class="col-md-3 col-lg-2 d-md-block admin-sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center text-white mb-4">
            <i class="bi bi-palette" style="font-size: 2rem;"></i>
            <h5 class="mt-2">Latido Cerámico</h5>
            <small>Panel Admin</small>
        </div>
        
        <ul class="nav flex-column">
            <?php 
            $menu_items = secciones_menu();
            foreach ($menu_items as $seccion_key => $item): 
                $active_class = ($seccion === $seccion_key) ? 'active bg-white bg-opacity-25' : '';
            ?>
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center py-3 <?= $active_class ?>" 
                       href="?sec=<?= $seccion_key ?>">
                        <i class="bi bi-<?= $item['icono'] ?> me-3" style="font-size: 1.1rem;"></i>
                        <?= $item['nombre'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
            
            <li class="nav-item ms-3 mt-2">
                <?php $active_new = ($seccion === 'crear_producto') ? 'active bg-white bg-opacity-25' : ''; ?>
                <a class="nav-link text-white d-flex align-items-center py-2 <?= $active_new ?>" href="?sec=crear_producto">
                    <i class="bi bi-plus-circle me-2"></i>
                    Agregar producto
                </a>
            </li>
        </ul>
        
        <hr class="text-white-50 my-4">
        
        <div class="text-center">
            <small class="text-white-50">
                Versión 1.0<br>
                <?= date('Y') ?> Latido Cerámico
            </small>
        </div>
    </div>
</nav>