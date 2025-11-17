<?php
require_once("classes/ProductoAdmin.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$producto = $id > 0 ? ProductoAdmin::obtenerPorId($id) : null;

if (!$producto) {
    echo '<div class="alert alert-warning">Producto no encontrado.</div>';
    echo '<a href="?sec=productos" class="btn btn-secondary">Volver</a>';
    return;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-trash text-danger me-2"></i>
        Eliminar producto #<?= $producto->getId(); ?>
    </h1>
</div>

<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle me-2"></i>
    ¿Estás seguro de que deseas eliminar definitivamente este producto? Esta acción no se puede deshacer.
</div>

<div class="card card-admin shadow mb-4">
    <div class="card-body d-flex align-items-center">
        <img src="../assets/img/productos/<?= htmlspecialchars($producto->getImagenPrincipal()); ?>" alt="<?= htmlspecialchars($producto->getNombre()); ?>" class="rounded me-3" style="width:120px;height:120px;object-fit:cover;">
        <div>
            <h5 class="mb-1"><?= htmlspecialchars($producto->getNombre()); ?></h5>
            <div class="text-muted">Precio: <?= formatear_precio($producto->getPrecio()); ?></div>
            <div class="small">Stock: <strong><?= (int)$producto->getStock(); ?></strong></div>
            <div class="small">Categoría: <span class="badge bg-secondary text-capitalize"><?= htmlspecialchars($producto->categoria_nombre ?? '') ?></span></div>
            <div class="small">Descripción: <?= htmlspecialchars($producto->getDescripcion()); ?></div>
        </div>
    </div>
</div>

<form action="actions/borrar_producto_acc.php" method="post">
    <input type="hidden" name="id" value="<?= $producto->getId(); ?>">
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="?sec=productos" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-danger">Eliminar</button>
    </div>
</form>
