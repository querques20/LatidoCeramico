<?php
require_once("classes/ProductoAdmin.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$producto = $id > 0 ? ProductoAdmin::obtenerPorId($id) : null;
$categorias = ProductoAdmin::obtenerCategorias();

if (!$producto) {
    echo '<div class="alert alert-warning">Producto no encontrado.</div>';
    echo '<a href="?sec=productos" class="btn btn-secondary">Volver</a>';
    return;
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-pencil-square text-warning me-2"></i>
        Editar producto #<?= $producto->getId(); ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="?sec=productos" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Volver a productos
        </a>
    </div>
    </div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card card-admin shadow">
            <div class="card-body">
                <form action="actions/editar_producto_acc.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $producto->getId(); ?>">

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="nombre" class="form-label">Nombre de Producto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($producto->getNombre()); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="categoria_id" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="" disabled>Seleccionar</option>
                                <?php foreach($categorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($producto->getCategoriaId() == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= htmlspecialchars($producto->getDescripcion()); ?></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" value="<?= htmlspecialchars($producto->getPrecio()); ?>" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" min="0" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($producto->getStock()); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="sku" class="form-label">SKU (opcional)</label>
                            <input type="text" class="form-control" id="sku" name="sku" maxlength="64" value="<?= htmlspecialchars($producto->getSku() ?? ''); ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label d-block">Imagen actual</label>
                            <img src="../assets/img/productos/<?= htmlspecialchars($producto->getImagenPrincipal()); ?>" alt="<?= htmlspecialchars($producto->getNombre()); ?>" class="rounded" style="width:120px;height:120px;object-fit:cover;">
                        </div>
                        <div class="col-md-8">
                            <label for="foto" class="form-label">Reemplazar imagen (opcional)</label>
                            <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                            <div class="form-text">Dejar vacío para mantener la imagen actual.</div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="?sec=productos" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
