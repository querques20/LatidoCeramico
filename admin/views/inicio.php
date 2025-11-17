<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-house-door text-primary me-2"></i>
        Dashboard - Panel de Administración
    </h1>
</div>

<?php
require_once("classes/ProductoAdmin.php");


$producto = new ProductoAdmin();
$lista = $producto->todosProductos();
$total_productos = count($lista);


$categorias_unicas = [];
foreach ($lista as $p) { $categorias_unicas[$p->categoria_nombre ?? 'Sin categoría'] = true; }
$total_categorias = count($categorias_unicas);


$productos_stock = [];
foreach ($lista as $p) {
    $productos_stock[] = [
        'nombre' => (string)$p->getNombre(),
        'stock' => max(0, (int)$p->getStock())
    ];
}
usort($productos_stock, fn($a,$b) => $b['stock'] <=> $a['stock']);
$max_stock_producto = $productos_stock ? max(array_column($productos_stock, 'stock')) : 0;



$productos_recientes = array_slice($lista, 0, 5);
?>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-admin border-left-primary shadow h-100 py-2" style="border-left: 4px solid #007bff;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Productos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_productos ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box-seam fa-2x text-gray-300" style="font-size: 2rem; color: #d1d3e2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-admin border-left-success shadow h-100 py-2" style="border-left: 4px solid #28a745;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Categorías
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_categorias ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-tags fa-2x text-gray-300" style="font-size: 2rem; color: #d1d3e2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-admin border-left-warning shadow h-100 py-2" style="border-left: 4px solid #ffc107;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Estado del Sistema
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <span class="badge bg-success">Activo</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-check-circle fa-2x text-gray-300" style="font-size: 2rem; color: #d1d3e2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card card-admin shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-bar-chart me-2"></i>
                    Stock por Producto
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($productos_stock)): ?>
                    <?php foreach ($productos_stock as $it): $cantidad = (int)$it['stock']; $nombre = $it['nombre']; ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-truncate" style="max-width: 70%" title="<?= htmlspecialchars($nombre) ?>"><?= htmlspecialchars($nombre) ?></span>
                                <span class="font-weight-bold"><?= $cantidad ?></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: <?= $max_stock_producto > 0 ? (($cantidad / $max_stock_producto) * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No hay datos disponibles</p>
                <?php endif; ?>
                <small class="text-muted">Listado de productos por stock. Barras relativas al mayor stock.</small>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card card-admin shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-clock-history me-2"></i>
                    Productos Recientes
                </h6>
                <a href="?sec=productos" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body">
                <?php if (!empty($productos_recientes)): ?>
                    <?php foreach ($productos_recientes as $prod): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <img src="../assets/img/productos/<?= htmlspecialchars($prod->getImagenPrincipal() ?? '') ?>" 
                                     alt="<?= htmlspecialchars($prod->getNombre()) ?>" 
                                     class="rounded" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-bold"><?= htmlspecialchars($prod->getNombre()) ?></div>
                                <small class="text-muted text-capitalize"><?= htmlspecialchars($prod->categoria_nombre ?? '') ?></small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge bg-primary"><?= formatear_precio($prod->getPrecio()) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No hay productos registrados</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-admin shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-lightning me-2"></i>
                    Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="?sec=crear_producto" class="btn btn-primary btn-lg d-block">
                            <i class="bi bi-plus-circle mb-2" style="font-size: 2rem;"></i><br>
                            Crear Producto
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="?sec=productos" class="btn btn-info btn-lg d-block">
                            <i class="bi bi-list-ul mb-2" style="font-size: 2rem;"></i><br>
                            Ver Productos
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="?sec=categorias" class="btn btn-success btn-lg d-block">
                            <i class="bi bi-tags mb-2" style="font-size: 2rem;"></i><br>
                            Gestionar Categorías
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="../index.php" target="_blank" class="btn btn-secondary btn-lg d-block">
                            <i class="bi bi-eye mb-2" style="font-size: 2rem;"></i><br>
                            Ver Sitio Web
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>