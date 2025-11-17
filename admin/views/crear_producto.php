<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-plus-circle text-success me-2"></i>
        Agregar un nuevo producto
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="?sec=productos" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Volver a productos
        </a>
    </div>
</div>

<?php
require_once('classes/Conexion.php');
require_once('classes/CategoriaAdmin.php');
$categorias = CategoriaAdmin::todas();
?>
<div class="row">
    <div class="col-lg-9 mx-auto">
        <div class="card card-admin shadow">
            <div class="card-body">
                <?php if(empty($categorias)): ?>
                    <div class="alert alert-warning">No hay categorías visibles. Crea una antes de cargar productos.</div>
                <?php endif; ?>
                <form action="actions/crear_producto_acc.php" method="post" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label for="categoria_id" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria_id" name="categoria_id" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach($categorias as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="col-md-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" required>
                    </div>
                    <div class="col-md-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" min="0" class="form-control" id="stock" name="stock" value="0" required>
                    </div>
                    <div class="col-md-4">
                        <label for="sku" class="form-label">SKU (opcional)</label>
                        <input type="text" class="form-control" id="sku" name="sku" maxlength="64">
                    </div>
                    <div class="col-md-5">
                        <label for="foto" class="form-label">Imagen Principal</label>
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/*" required>
                        <div class="form-text">Formatos: JPG, PNG, WEBP (máx 2MB)</div>
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <a href="?sec=productos" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-success">Crear Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>